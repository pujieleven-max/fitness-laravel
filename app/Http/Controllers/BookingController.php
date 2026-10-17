<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FitnessClass;
use App\Models\Member;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['trainer', 'fitnessClass', 'member', 'user'])->latest('booking_date');

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        if ($request->filled('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        $trainers = Trainer::where('is_active', true)->get();
        $fitnessClasses = FitnessClass::where('is_active', true)->get();
        $members = Member::where('status', 'active')->get();

        // Metrics for summary header
        $today = Carbon::today();
        $todayBookingsCount = Booking::whereDate('booking_date', $today)->count();
        $pendingCount = Booking::where('status', 'pending')->count();
        $unpaidCount = Booking::where('payment_status', 'unpaid')->where('status', '!=', 'cancelled')->count();
        $completedCount = Booking::where('status', 'completed')->count();

        return view('bookings.index', compact(
            'bookings',
            'trainers',
            'fitnessClasses',
            'members',
            'todayBookingsCount',
            'pendingCount',
            'unpaidCount',
            'completedCount'
        ));
    }

    public function create()
    {
        $trainers = Trainer::where('is_active', true)->get();
        $fitnessClasses = FitnessClass::where('is_active', true)->get();
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('bookings.create', compact('trainers', 'fitnessClasses', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_type' => 'required|in:personal_trainer,fitness_class,gym_slot',
            'member_id' => 'nullable|exists:members,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'trainer_id' => 'nullable|exists:trainers,id',
            'fitness_class_id' => 'nullable|exists:fitness_classes,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid',
            'notes' => 'nullable|string',
        ]);

        // Auto generate booking code BK-YYYYMMDD-XXXX
        $datePrefix = Carbon::parse($validated['booking_date'])->format('Ymd');
        $latest = Booking::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = $latest ? (int) substr($latest->booking_code, -4) + 1 : 1;
        $bookingCode = 'BK-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $validated['booking_code'] = $bookingCode;
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'confirmed';

        // Check if member selected, update name/phone if empty
        if (! empty($validated['member_id'])) {
            $member = Member::find($validated['member_id']);
            if ($member) {
                $validated['customer_name'] = $member->name;
                $validated['customer_phone'] = $member->phone ?: $validated['customer_phone'];
            }
        }

        $booking = Booking::create($validated);

        return redirect()->route('bookings.index')
            ->with('success', "Booking {$booking->booking_code} atas nama {$booking->customer_name} berhasil dibuat!");
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
        ]);

        $updates = [];
        if ($request->filled('status')) {
            $updates['status'] = $request->status;
        }
        if ($request->filled('payment_status')) {
            $updates['payment_status'] = $request->payment_status;
        }

        $booking->update($updates);

        return back()->with('success', "Status booking {$booking->booking_code} berhasil diperbarui.");
    }

    public function destroy(Booking $booking)
    {
        $code = $booking->booking_code;
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', "Booking {$code} berhasil dihapus.");
    }

    public function calendar(Request $request)
    {
        $trainers = Trainer::where('is_active', true)->get();
        $classes = FitnessClass::where('is_active', true)->with('trainer')->get();

        // Get bookings for the selected week or month
        $selectedDate = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek = $selectedDate->copy()->endOfWeek();

        $weekBookings = Booking::with(['trainer', 'fitnessClass', 'member'])
            ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return view('bookings.calendar', compact('trainers', 'classes', 'selectedDate', 'startOfWeek', 'endOfWeek', 'weekBookings'));
    }
}
