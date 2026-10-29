<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\FitnessClass;
use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Setting;
use App\Models\Supplement;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Render the public promotional landing page for the fitness center.
     */
    public function index()
    {
        $packages = GymPackage::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $trainers = Trainer::where('is_active', true)
            ->withCount(['classes' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();

        $classes = FitnessClass::where('is_active', true)
            ->with('trainer')
            ->get();

        $supplements = Supplement::where('is_active', true)
            ->with('category')
            ->orderByRaw('CASE WHEN stock > 0 THEN 0 ELSE 1 END')
            ->orderBy('name')
            ->get();

        $supplementCategories = Category::whereHas('supplements', function ($q) {
            $q->where('is_active', true);
        })->get();

        $stats = [
            'active_members' => max(Member::where('status', 'active')->count(), 120),
            'total_trainers' => $trainers->count(),
            'total_classes' => $classes->count(),
            'total_supplements' => $supplements->where('stock', '>', 0)->count(),
        ];

        return view('public.landing', compact(
            'packages',
            'trainers',
            'classes',
            'supplements',
            'supplementCategories',
            'stats'
        ));
    }

    /**
     * Check Membership or Booking status via AJAX from the public landing page.
     */
    public function checkStatus(Request $request)
    {
        $query = trim((string) $request->input('query', ''));

        if (strlen($query) < 3) {
            return response()->json([
                'found' => false,
                'message' => 'Masukkan minimal 3 karakter (Kode Member, Kode Booking, atau Nomor WhatsApp).',
            ], 422);
        }

        // 1. Check if it matches a Booking Code first
        if (str_starts_with(strtoupper($query), 'BK-')) {
            $booking = Booking::with(['trainer', 'fitnessClass'])
                ->where('booking_code', strtoupper($query))
                ->first();

            if ($booking) {
                return response()->json([
                    'found' => true,
                    'type' => 'booking',
                    'data' => [
                        'booking_code' => $booking->booking_code,
                        'customer_name' => $booking->customer_name,
                        'booking_type_label' => $booking->booking_type === 'personal_trainer'
                            ? 'Personal Trainer 1-on-1'
                            : ($booking->booking_type === 'fitness_class' ? 'Kelas Studio Fitness' : 'Sesi Latihan Gym'),
                        'service_name' => $booking->booking_type === 'personal_trainer'
                            ? ($booking->trainer->name ?? 'Personal Trainer')
                            : ($booking->fitnessClass->name ?? 'Kelas Gym'),
                        'booking_date' => $booking->booking_date ? $booking->booking_date->translatedFormat('d F Y') : '-',
                        'time_range' => substr($booking->start_time, 0, 5) . ' - ' . substr($booking->end_time, 0, 5) . ' WIB',
                        'price' => number_format((float) $booking->price, 0, ',', '.'),
                        'status' => $booking->status,
                        'payment_status' => $booking->payment_status,
                    ],
                ]);
            }
        }

        // 2. Search Member by member_code or phone
        $normalizedDigits = preg_replace('/[^0-9]/', '', $query);
        $member = Member::where('member_code', strtoupper($query))
            ->when(strlen($normalizedDigits) >= 6, function ($q) use ($normalizedDigits, $query) {
                $q->orWhere('phone', 'like', "%{$query}%")
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone, '-', ''), ' ', ''), '+', '') LIKE ?", ["%{$normalizedDigits}%"]);
            })
            ->first();

        if ($member) {
            $isActive = $member->isMembershipActive();
            $daysRemaining = ($member->membership_expires_at && $member->membership_expires_at->isFuture())
                ? (int) Carbon::today()->diffInDays($member->membership_expires_at, false)
                : 0;

            $recentBookings = Booking::with(['trainer', 'fitnessClass'])
                ->where('member_id', $member->id)
                ->latest('booking_date')
                ->take(3)
                ->get()
                ->map(fn ($b) => [
                    'booking_code' => $b->booking_code,
                    'service' => $b->booking_type === 'personal_trainer'
                        ? ($b->trainer->name ?? 'Personal Trainer')
                        : ($b->fitnessClass->name ?? 'Kelas Studio'),
                    'date' => $b->booking_date ? $b->booking_date->format('d/m/Y') : '-',
                    'time' => substr($b->start_time, 0, 5),
                    'status' => $b->status,
                ]);

            return response()->json([
                'found' => true,
                'type' => 'member',
                'data' => [
                    'member_code' => $member->member_code,
                    'name' => $member->name,
                    'membership_type' => strtoupper(str_replace('_', ' ', $member->membership_type ?? 'None')),
                    'status' => $isActive ? 'active' : 'expired',
                    'is_active' => $isActive,
                    'start_date' => $member->membership_start ? $member->membership_start->format('d M Y') : '-',
                    'expires_at' => $member->membership_expires_at ? $member->membership_expires_at->format('d M Y') : 'Belum Berlangganan',
                    'days_remaining' => max(0, $daysRemaining),
                    'recent_bookings' => $recentBookings,
                ],
            ]);
        }

        // 3. Fallback: check booking by customer_phone
        $bookingByPhone = Booking::with(['trainer', 'fitnessClass'])
            ->where('customer_phone', 'like', "%{$query}%")
            ->latest('booking_date')
            ->first();

        if ($bookingByPhone) {
            return response()->json([
                'found' => true,
                'type' => 'booking',
                'data' => [
                    'booking_code' => $bookingByPhone->booking_code,
                    'customer_name' => $bookingByPhone->customer_name,
                    'booking_type_label' => $bookingByPhone->booking_type === 'personal_trainer'
                        ? 'Personal Trainer 1-on-1'
                        : ($bookingByPhone->booking_type === 'fitness_class' ? 'Kelas Studio Fitness' : 'Sesi Latihan Gym'),
                    'service_name' => $bookingByPhone->booking_type === 'personal_trainer'
                        ? ($bookingByPhone->trainer->name ?? 'Personal Trainer')
                        : ($bookingByPhone->fitnessClass->name ?? 'Kelas Gym'),
                    'booking_date' => $bookingByPhone->booking_date ? $bookingByPhone->booking_date->translatedFormat('d F Y') : '-',
                    'time_range' => substr($bookingByPhone->start_time, 0, 5) . ' - ' . substr($bookingByPhone->end_time, 0, 5) . ' WIB',
                    'price' => number_format((float) $bookingByPhone->price, 0, ',', '.'),
                    'status' => $bookingByPhone->status,
                    'payment_status' => $bookingByPhone->payment_status,
                ],
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Data Member atau Booking tidak ditemukan. Pastikan Kode Member (contoh: MBR-1001), Kode Booking, atau No. HP sudah benar.',
        ], 404);
    }

    /**
     * Store an online booking request submitted by a visitor from the public landing page.
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'booking_type' => 'required|in:personal_trainer,fitness_class,gym_slot',
            'customer_name' => 'required|string|max:120',
            'customer_phone' => 'required|string|max:40',
            'member_code' => 'nullable|string|max:40',
            'trainer_id' => 'nullable|exists:trainers,id',
            'fitness_class_id' => 'nullable|exists:fitness_classes,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        // Calculate price and end_time based on selection
        $price = 35000;
        $serviceLabel = 'Daily Pass / Kunjungan Harian Gym';
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHour();
        $trainerId = $validated['trainer_id'] ?? null;
        $classId = $validated['fitness_class_id'] ?? null;

        if ($validated['booking_type'] === 'personal_trainer' && $trainerId) {
            $trainer = Trainer::find($trainerId);
            if ($trainer) {
                $price = (float) $trainer->hourly_rate;
                $serviceLabel = 'Personal Trainer: ' . $trainer->name;
            }
        } elseif ($validated['booking_type'] === 'fitness_class' && $classId) {
            $fitnessClass = FitnessClass::find($classId);
            if ($fitnessClass) {
                $price = (float) $fitnessClass->price_per_session;
                $trainerId = $fitnessClass->trainer_id;
                $startTime = Carbon::parse($fitnessClass->start_time);
                $endTime = Carbon::parse($fitnessClass->end_time);
                $serviceLabel = 'Kelas Studio: ' . $fitnessClass->name . ' (' . $fitnessClass->schedule_day . ')';
            }
        } else {
            $dailyPass = GymPackage::where('is_active', true)->where('type', 'daily')->orderBy('price')->first();
            if ($dailyPass) {
                $price = (float) $dailyPass->price;
                $serviceLabel = $dailyPass->name;
            }
        }

        // Match member if member_code or phone exists
        $member = null;
        if (! empty($validated['member_code'])) {
            $member = Member::where('member_code', strtoupper(trim($validated['member_code'])))->first();
        }
        if (! $member && ! empty($validated['customer_phone'])) {
            $member = Member::where('phone', trim($validated['customer_phone']))->first();
        }

        // Generate unique booking code BK-YYYYMMDD-XXXX
        $datePrefix = Carbon::parse($validated['booking_date'])->format('Ymd');
        $latest = Booking::where('booking_code', 'like', "BK-{$datePrefix}-%")->latest('id')->first();
        $sequence = $latest ? ((int) substr($latest->booking_code, -4)) + 1 : 1;
        $bookingCode = 'BK-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        while (Booking::where('booking_code', $bookingCode)->exists()) {
            $sequence++;
            $bookingCode = 'BK-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'member_id' => $member?->id,
            'customer_name' => $member ? $member->name : $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'booking_type' => $validated['booking_type'],
            'trainer_id' => $trainerId,
            'fitness_class_id' => $classId,
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'price' => $price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => trim(($validated['notes'] ?? '') . ' [Reservasi Online via Website]'),
        ]);

        return redirect()->route('landing')
            ->with('booking_success', [
                'code' => $booking->booking_code,
                'name' => $booking->customer_name,
                'phone' => $booking->customer_phone,
                'service' => $serviceLabel,
                'date' => Carbon::parse($booking->booking_date)->translatedFormat('d F Y'),
                'time' => substr($booking->start_time, 0, 5) . ' - ' . substr($booking->end_time, 0, 5) . ' WIB',
                'price' => number_format((float) $booking->price, 0, ',', '.'),
            ]);
    }
}

