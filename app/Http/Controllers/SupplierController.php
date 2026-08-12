<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $suppliers = Supplier::when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('country', 'like', "%{$request->search}%"))
            ->when($request->filled('country'), fn($q) => $q->where('country', $request->country))
            ->orderBy('type')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.suppliers.index', [
            'suppliers' => $suppliers,
            'types' => Supplier::$types,
            'countries' => Supplier::distinct()->orderBy('country')->pluck('country'),
        ]);
    }

    public function show(Supplier $supplier): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('admin.suppliers.edit', $supplier);
    }

    public function create(): View
    {
        return view('admin.suppliers.form', ['supplier' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(Supplier::$types)),
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'gps_coordinates' => 'nullable|string|max:100',
            'classification' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('admin.suppliers.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(Supplier::$types)),
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'gps_coordinates' => 'nullable|string|max:100',
            'classification' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted.');
    }
}
