<?php

namespace App\Http\Controllers;

use App\Models\GymPackage;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GymPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = GymPackage::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $packages = $query->paginate(12)->withQueryString();

        // Metrics for summary cards
        $totalActive = GymPackage::where('is_active', true)->count();
        $totalDaily = GymPackage::where('type', 'daily')->where('is_active', true)->count();
        $totalMonthly = GymPackage::whereIn('type', ['monthly', 'annual'])->where('is_active', true)->count();

        // Total sales count for packages from OrderItem
        $totalPackageSalesCount = OrderItem::where('item_type', 'gym_package')->sum('quantity');

        // Suggest next code
        $latest = GymPackage::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $suggestedCode = 'PKG-' . strtoupper(Str::random(3)) . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('packages.index', compact(
            'packages',
            'totalActive',
            'totalDaily',
            'totalMonthly',
            'totalPackageSalesCount',
            'suggestedCode'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50|unique:gym_packages,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:daily,monthly,annual,session',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['code'])) {
            $prefix = strtoupper($validated['type']);
            $latest = GymPackage::where('type', $validated['type'])->latest('id')->first();
            $seq = $latest ? $latest->id + 1 : 1;
            $validated['code'] = 'PKG-' . $prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $package = GymPackage::create($validated);

        return back()->with('success', "Paket Gym '{$package->name}' ({$package->code}) berhasil ditambahkan dan siap dijual di Kasir POS!");
    }

    public function update(Request $request, GymPackage $gymPackage)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:gym_packages,code,' . $gymPackage->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:daily,monthly,annual,session',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $gymPackage->update($validated);

        return back()->with('success', "Paket Gym '{$gymPackage->name}' berhasil diperbarui.");
    }

    public function destroy(GymPackage $gymPackage)
    {
        if (! Auth::user()->isOwner()) {
            return back()->with('error', 'Hanya Owner yang berhak menghapus data paket tiket gym.');
        }

        $name = $gymPackage->name;
        $gymPackage->delete();

        return back()->with('success', "Paket Gym '{$name}' berhasil dihapus.");
    }

    public function toggle(GymPackage $gymPackage)
    {
        $gymPackage->update(['is_active' => ! $gymPackage->is_active]);

        $statusStr = $gymPackage->is_active ? 'diaktifkan (tampil di Kasir)' : 'dinonaktifkan (disembunyikan dari Kasir)';

        return back()->with('success', "Paket '{$gymPackage->name}' berhasil {$statusStr}.");
    }
}
