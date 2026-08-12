<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ModuleRecordController extends Controller
{
    public function index(string $slug): View
    {
        $title = Str::of($slug)->replace('-', ' ')->title()->toString();

        return view('admin.records.index', [
            'slug' => $slug,
            'title' => $title,
            'records' => DB::table('module_records')->where('module_slug', $slug)->latest()->paginate(20),
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $data = $this->validated($request);
        DB::table('module_records')->insert([
            ...$data, 'module_slug' => $slug, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Record created.');
    }

    public function update(Request $request, string $slug, int $record): RedirectResponse
    {
        DB::table('module_records')->where('module_slug', $slug)->where('id', $record)
            ->update([...$this->validated($request), 'updated_at' => now()]);

        return back()->with('success', 'Record updated.');
    }

    public function destroy(string $slug, int $record): RedirectResponse
    {
        DB::table('module_records')->where('module_slug', $slug)->where('id', $record)->delete();

        return back()->with('success', 'Record deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'reference' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'effective_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
