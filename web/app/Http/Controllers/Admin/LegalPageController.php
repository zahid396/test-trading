<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function index(): View
    {
        $pages = LegalPage::latest()->get();

        return view('admin.legal-pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.legal-pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|unique:legal_pages,slug',
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        LegalPage::create($validated);

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page created successfully.');
    }

    public function edit(LegalPage $legalPage): View
    {
        return view('admin.legal-pages.edit', ['page' => $legalPage]);
    }

    public function update(Request $request, LegalPage $legalPage): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => 'required|unique:legal_pages,slug,' . $legalPage->id,
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $legalPage->update($validated);

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page updated successfully.');
    }

    public function destroy(LegalPage $legalPage): RedirectResponse
    {
        $legalPage->delete();

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page deleted successfully.');
    }
}
