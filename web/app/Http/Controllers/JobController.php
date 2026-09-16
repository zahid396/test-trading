<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobPosting::active();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        $jobs = $query
            ->select(['id', 'title', 'slug', 'company_name', 'location', 'employment_type', 'salary_range', 'application_deadline', 'subtitle', 'image', 'sort_order'])
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $types = collect(['Full-time', 'Part-time', 'Contract', 'Internship', 'Remote']);

        return view('jobs.index', compact('jobs', 'types'));
    }

    public function show(string $slug): View
    {
        $job = JobPosting::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = JobPosting::active()
            ->where('id', '!=', $job->id)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('jobs.show', compact('job', 'related'));
    }

    public function apply(Request $request, JobPosting $job): RedirectResponse
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'cover_letter' => 'nullable|string|max:5000',
        ]);

        if (! $job->isOpen()) {
            return back()->with('error', 'Applications for this job are currently closed.');
        }

        JobApplication::create([
            'job_posting_id' => $job->id,
            'applicant_name' => $validated['applicant_name'],
            'applicant_email' => $validated['applicant_email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your application has been submitted successfully. We will contact you shortly.');
    }
}
