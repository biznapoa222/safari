<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActivityCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.activity-categories.index', [
            'categories' => ActivityCategory::orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.activity-categories.form', ['category' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        ActivityCategory::create($data);

        return redirect()->route('admin.activity-categories.index')->with('success', 'Category created.');
    }

    public function edit(ActivityCategory $activityCategory): View
    {
        return view('admin.activity-categories.form', ['category' => $activityCategory]);
    }

    public function update(Request $request, ActivityCategory $activityCategory): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories,name,' . $activityCategory->id,
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        $activityCategory->update($data);

        return redirect()->route('admin.activity-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ActivityCategory $activityCategory): RedirectResponse
    {
        $activityCategory->delete();
        return redirect()->route('admin.activity-categories.index')->with('success', 'Category deleted.');
    }
}
