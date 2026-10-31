<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAssessment;
use App\Models\Setting;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemberAssessmentController extends Controller
{
    /**
     * Display a listing of assessments.
     */
    public function index(Request $request)
    {
        $query = MemberAssessment::with(['member', 'trainer', 'user'])->latest('assessment_date')->latest('id');

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('assessment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('assessment_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $assessments = $query->paginate(15)->withQueryString();

        // Statistics
        $totalAssessments = MemberAssessment::count();
        $uniqueMembersCount = MemberAssessment::distinct('member_id')->count('member_id');
        $thisMonthCount = MemberAssessment::whereMonth('assessment_date', Carbon::now()->month)
            ->whereYear('assessment_date', Carbon::now()->year)
            ->count();
        $trainersCount = Trainer::where('is_active', true)->count();

        $members = Member::orderBy('name')->get(['id', 'name', 'member_code']);
        $trainers = Trainer::where('is_active', true)->orderBy('name')->get(['id', 'name', 'specialization']);

        return view('assessments.index', compact(
            'assessments',
            'totalAssessments',
            'uniqueMembersCount',
            'thisMonthCount',
            'trainersCount',
            'members',
            'trainers'
        ));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create(Request $request)
    {
        $members = Member::orderBy('name')->get();
        $trainers = Trainer::where('is_active', true)->orderBy('name')->get();

        $selectedMember = null;
        $previousAssessment = null;

        if ($request->filled('member_id')) {
            $selectedMember = Member::find($request->member_id);
            if ($selectedMember) {
                $previousAssessment = $selectedMember->latestAssessment;
            }
        }

        return view('assessments.create', compact('members', 'trainers', 'selectedMember', 'previousAssessment'));
    }

    /**
     * Store a newly created assessment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'assessment_date' => 'required|date',
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'nullable|numeric|min:50|max:250',
            'body_fat_percentage' => 'nullable|numeric|min:1|max:70',
            'muscle_mass' => 'nullable|numeric|min:5|max:150',
            'body_water_percentage' => 'nullable|numeric|min:10|max:80',
            'visceral_fat' => 'nullable|integer|min:1|max:59',
            'bone_mass' => 'nullable|numeric|min:0.5|max:10',
            'bmr' => 'nullable|integer|min:500|max:5000',
            'metabolic_age' => 'nullable|integer|min:10|max:100',
            'chest_circumference' => 'nullable|numeric|min:20|max:200',
            'waist_circumference' => 'nullable|numeric|min:20|max:200',
            'hips_circumference' => 'nullable|numeric|min:20|max:200',
            'arm_circumference' => 'nullable|numeric|min:10|max:100',
            'thigh_circumference' => 'nullable|numeric|min:10|max:150',
            'calf_circumference' => 'nullable|numeric|min:10|max:100',
            'fitness_goal' => 'nullable|string|max:100',
            'trainer_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Auto calculate BMI
        $height = ! empty($validated['height']) ? (float) $validated['height'] : null;
        $bmiMetrics = MemberAssessment::calculateBmiMetrics((float) $validated['weight'], $height);
        $validated['bmi'] = $bmiMetrics['bmi'];
        $validated['bmi_category'] = $bmiMetrics['category'];

        // Assign current authenticated user (trainer / kasir / owner)
        $validated['user_id'] = Auth::id();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('assessments', 'public');
            $validated['photo_path'] = $path;
        }

        $assessment = MemberAssessment::create($validated);

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Asesmen kebugaran & penimbangan berhasil disimpan untuk member ' . $assessment->member->name . '!');
    }

    /**
     * Display the specified assessment details.
     */
    public function show(MemberAssessment $assessment)
    {
        $assessment->load(['member', 'trainer', 'user']);
        $previous = $assessment->getPreviousAssessment();

        // All assessments for this member to build transformation timeline
        $memberTimeline = MemberAssessment::where('member_id', $assessment->member_id)
            ->orderBy('assessment_date', 'asc')
            ->get();

        return view('assessments.show', compact('assessment', 'previous', 'memberTimeline'));
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(MemberAssessment $assessment)
    {
        $members = Member::orderBy('name')->get();
        $trainers = Trainer::where('is_active', true)->orderBy('name')->get();

        return view('assessments.edit', compact('assessment', 'members', 'trainers'));
    }

    /**
     * Update the specified assessment.
     */
    public function update(Request $request, MemberAssessment $assessment)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'assessment_date' => 'required|date',
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'nullable|numeric|min:50|max:250',
            'body_fat_percentage' => 'nullable|numeric|min:1|max:70',
            'muscle_mass' => 'nullable|numeric|min:5|max:150',
            'body_water_percentage' => 'nullable|numeric|min:10|max:80',
            'visceral_fat' => 'nullable|integer|min:1|max:59',
            'bone_mass' => 'nullable|numeric|min:0.5|max:10',
            'bmr' => 'nullable|integer|min:500|max:5000',
            'metabolic_age' => 'nullable|integer|min:10|max:100',
            'chest_circumference' => 'nullable|numeric|min:20|max:200',
            'waist_circumference' => 'nullable|numeric|min:20|max:200',
            'hips_circumference' => 'nullable|numeric|min:20|max:200',
            'arm_circumference' => 'nullable|numeric|min:10|max:100',
            'thigh_circumference' => 'nullable|numeric|min:10|max:150',
            'calf_circumference' => 'nullable|numeric|min:10|max:100',
            'fitness_goal' => 'nullable|string|max:100',
            'trainer_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $height = ! empty($validated['height']) ? (float) $validated['height'] : null;
        $bmiMetrics = MemberAssessment::calculateBmiMetrics((float) $validated['weight'], $height);
        $validated['bmi'] = $bmiMetrics['bmi'];
        $validated['bmi_category'] = $bmiMetrics['category'];

        if ($request->hasFile('photo')) {
            if ($assessment->photo_path) {
                Storage::disk('public')->delete($assessment->photo_path);
            }
            $path = $request->file('photo')->store('assessments', 'public');
            $validated['photo_path'] = $path;
        }

        $assessment->update($validated);

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Data asesmen kebugaran berhasil diperbarui.');
    }

    /**
     * Remove the specified assessment from storage.
     */
    public function destroy(MemberAssessment $assessment)
    {
        $memberName = $assessment->member?->name ?? 'Member';
        $date = $assessment->assessment_date?->format('d/m/Y');

        if ($assessment->photo_path) {
            Storage::disk('public')->delete($assessment->photo_path);
        }

        $assessment->delete();

        return redirect()->route('assessments.index')
            ->with('success', "Data asesmen kebugaran {$memberName} ({$date}) berhasil dihapus.");
    }

    /**
     * View complete transformation journey and history of a specific member.
     */
    public function memberHistory(Member $member)
    {
        $assessments = $member->assessments()->with(['trainer', 'user'])->get();

        $firstAssessment = $assessments->last(); // oldest
        $latestAssessment = $assessments->first(); // newest

        // Calculations
        $weightChange = null;
        $fatChange = null;
        $muscleChange = null;

        if ($firstAssessment && $latestAssessment && $firstAssessment->id !== $latestAssessment->id) {
            $weightChange = round($latestAssessment->weight - $firstAssessment->weight, 1);
            if ($firstAssessment->body_fat_percentage && $latestAssessment->body_fat_percentage) {
                $fatChange = round($latestAssessment->body_fat_percentage - $firstAssessment->body_fat_percentage, 1);
            }
            if ($firstAssessment->muscle_mass && $latestAssessment->muscle_mass) {
                $muscleChange = round($latestAssessment->muscle_mass - $firstAssessment->muscle_mass, 1);
            }
        }

        return view('assessments.member', compact(
            'member',
            'assessments',
            'firstAssessment',
            'latestAssessment',
            'weightChange',
            'fatChange',
            'muscleChange'
        ));
    }

    /**
     * Printable progress card for member.
     */
    public function printCard(MemberAssessment $assessment)
    {
        $assessment->load(['member', 'trainer', 'user']);
        $previous = $assessment->getPreviousAssessment();
        $appSetting = Setting::current();

        return view('assessments.print', compact('assessment', 'previous', 'appSetting'));
    }
}

