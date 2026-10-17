<?php

namespace App\Http\Controllers;

use App\Models\FitnessClass;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount('bookings', 'classes')->latest()->get();
        $classes = FitnessClass::with('trainer')->withCount('bookings')->latest()->get();

        return view('trainers.index', compact('trainers', 'classes'));
    }

    public function storeTrainer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'specialization' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        $trainer = Trainer::create($validated);

        return back()->with('success', "Personal Trainer '{$trainer->name}' berhasil ditambahkan!");
    }

    public function updateTrainer(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'specialization' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        $trainer->update($validated);

        return back()->with('success', "Data Trainer '{$trainer->name}' berhasil diperbarui.");
    }

    public function destroyTrainer(Trainer $trainer)
    {
        $name = $trainer->name;
        $trainer->delete();

        return back()->with('success', "Trainer '{$name}' berhasil dihapus.");
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trainer_id' => 'nullable|exists:trainers,id',
            'category' => 'required|string|max:100',
            'schedule_day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'price_per_session' => 'required|numeric|min:0',
        ]);

        $class = FitnessClass::create($validated);

        return back()->with('success', "Kelas Fitness '{$class->name}' berhasil dijadwalkan!");
    }

    public function updateClass(Request $request, FitnessClass $fitnessClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trainer_id' => 'nullable|exists:trainers,id',
            'category' => 'required|string|max:100',
            'schedule_day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'price_per_session' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $fitnessClass->update($validated);

        return back()->with('success', "Kelas Fitness '{$fitnessClass->name}' berhasil diperbarui.");
    }

    public function destroyClass(FitnessClass $fitnessClass)
    {
        $name = $fitnessClass->name;
        $fitnessClass->delete();

        return back()->with('success', "Kelas '{$name}' berhasil dihapus.");
    }
}
