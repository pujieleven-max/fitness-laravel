<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::withCount('orders', 'bookings')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('membership_type')) {
            $query->where('membership_type', $request->membership_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(15)->withQueryString();

        $activeCount = Member::where('status', 'active')->count();
        $expiredCount = Member::where('status', 'expired')->count();
        $totalMembers = Member::count();

        return view('members.index', compact('members', 'activeCount', 'expiredCount', 'totalMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'membership_type' => 'required|string',
            'membership_start' => 'nullable|date',
            'membership_expires_at' => 'nullable|date',
        ]);

        // Auto generate member code MBR-XXXX
        $latest = Member::latest('id')->first();
        $nextId = $latest ? $latest->id + 1001 : 1001;
        $validated['member_code'] = 'MBR-' . $nextId;
        $validated['status'] = 'active';

        $member = Member::create($validated);

        return back()->with('success', "Member baru '{$member->name}' ({$member->member_code}) berhasil didaftarkan!");
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'membership_type' => 'required|string',
            'membership_start' => 'nullable|date',
            'membership_expires_at' => 'nullable|date',
            'status' => 'required|in:active,expired,inactive',
        ]);

        $member->update($validated);

        return back()->with('success', "Data member '{$member->name}' berhasil diperbarui.");
    }

    public function destroy(Member $member)
    {
        $name = $member->name;
        $member->delete();

        return back()->with('success', "Member '{$name}' berhasil dihapus.");
    }
}
