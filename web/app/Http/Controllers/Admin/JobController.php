<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobPosting::withCount('applications');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $jobs = $query->orderBy('sort_order')->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:job_postings,slug',
            'company_name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'employment_type' => 'nullable|max:255',
            'salary_range' => 'nullable|max:255',
            'application_deadline' => 'nullable|date',
            'subtitle' => 'nullable|max:255',
            'description' => 'nullable',
            'requirements' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($request, &$validated) {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time().'_'.Str::slug($validated['title']).'.'.$file->getClientOriginalExtension();
                $file->storeAs('jobs', $filename, 'public');
                $validated['image'] = $filename;
            }

            JobPosting::create($validated);
        });

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function edit(JobPosting $job): View
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPosting $job): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:job_postings,slug,'.$job->id,
            'company_name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'employment_type' => 'nullable|max:255',
            'salary_range' => 'nullable|max:255',
            'application_deadline' => 'nullable|date',
            'subtitle' => 'nullable|max:255',
            'description' => 'nullable',
            'requirements' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($request, $job, &$validated) {
            if ($request->hasFile('image')) {
                if ($job->image) {
                    Storage::disk('public')->delete('jobs/'.$job->image);
                }

                $file = $request->file('image');
                $filename = time().'_'.Str::slug($validated['title']).'.'.$file->getClientOriginalExtension();
                $file->storeAs('jobs', $filename, 'public');
                $validated['image'] = $filename;
            } else {
                unset($validated['image']);
            }

            $job->update($validated);
        });

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job): RedirectResponse
    {
        DB::transaction(function () use ($job) {
            if ($job->image) {
                Storage::disk('public')->delete('jobs/'.$job->image);
            }

            $job->delete();
        });

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting deleted successfully.');
    }

    public function toggle(JobPosting $job): JsonResponse
    {
        $job->update(['is_active' => ! $job->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $job->is_active,
        ]);
    }

    public function applications(Request $request): View
    {
        $query = JobApplication::with('jobPosting')->latest();

        if ($jobId = $request->input('job')) {
            $query->where('job_posting_id', $jobId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                    ->orWhere('applicant_email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(20)->withQueryString();
        $jobs = JobPosting::orderBy('title')->get();

        return view('admin.jobs.applications', compact('applications', 'jobs'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shortlisted,rejected,hired',
        ]);

        $application->update(['status' => $validated['status']]);

        return redirect()->route('admin.jobs.applications', ['job' => $application->job_posting_id])
            ->with('success', 'Application status updated.');
    }
}
