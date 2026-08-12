<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        return view('admin.locations.index', [
            'countries' => Country::with('regions')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.locations.country-form', ['country' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|size:3|unique:countries',
            'name' => 'required|string|max:255|unique:countries',
        ]);
        $data['slug'] = Str::slug($data['name']);

        Country::create($data);

        return redirect()->route('admin.countries.index')->with('success', 'Country created.');
    }

    public function edit(Country $country): View
    {
        return view('admin.locations.country-form', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|size:3|unique:countries,code,' . $country->id,
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
        ]);
        $data['slug'] = Str::slug($data['name']);

        $country->update($data);

        return redirect()->route('admin.countries.index')->with('success', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->regions()->count() > 0) {
            return back()->with('error', 'Cannot delete country with regions. Remove regions first.');
        }
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success', 'Country deleted.');
    }

    public function storeRegion(Request $request, Country $country): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['country_id'] = $country->id;

        Region::create($data);

        return back()->with('success', 'Region added.');
    }

    public function updateRegion(Request $request, Country $country, Region $region): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        $region->update($data);

        return back()->with('success', 'Region updated.');
    }

    public function destroyRegion(Country $country, Region $region): RedirectResponse
    {
        $region->delete();
        return back()->with('success', 'Region deleted.');
    }
}
