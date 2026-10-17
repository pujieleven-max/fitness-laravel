<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $totalOwners = User::where('role', 'owner')->count();
        $totalKasir = User::where('role', 'kasir')->count();

        return view('users.index', compact('users', 'totalOwners', 'totalKasir'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:owner,kasir',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        return back()->with('success', "Pengguna baru '{$user->name}' ({$user->role}) berhasil ditambahkan!");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:owner,kasir',
            'phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:6',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Prevent self-demotion of owner if they are the only active owner
        if ($user->id === Auth::id() && $validated['role'] !== 'owner') {
            return back()->with('error', 'Anda tidak dapat menurunkan peran (demote) akun Anda sendiri.');
        }

        $user->update($validated);

        return back()->with('success', "Data pengguna '{$user->name}' berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Pengguna '{$name}' berhasil dihapus.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun '{$user->name}' berhasil {$statusStr}.");
    }
}
