@extends('layouts.admin')
@section('title', 'Countries & Regions')
@section('content')
<x-admin.top-bar
    title="Countries & Regions"
    description="Location Management"
    addLabel="New Country"
    addRoute="{{ route('admin.countries.create') }}"
    :search="false"
/>
@include('admin.partials.flash')
@foreach($countries as $country)
<section class="ops-panel" style="margin-bottom:1rem;">
    <div class="ops-panel-title">
        <div><h2>{{ $country->name }} <span class="badge">{{ $country->code }}</span></h2></div>
        <div style="display:flex;gap:0.5rem;">
            <a href="{{ route('admin.countries.edit', $country) }}" class="button button-sm button-secondary">Edit</a>
            <form method="POST" action="{{ route('admin.countries.destroy', $country) }}" onsubmit="return confirm('Delete country?')">@csrf @method('DELETE')<button class="button button-sm button-danger">Delete</button></form>
        </div>
    </div>
    <div class="regions-list">
        @foreach($country->regions as $region)
        <div class="region-item">
            <span>{{ $region->name }}</span>
            <div style="display:flex;gap:0.25rem;">
                <form method="POST" action="{{ route('admin.countries.regions.update', [$country, $region]) }}" style="display:contents;">
                    @csrf @method('PUT')
                    <input name="name" value="{{ $region->name }}" class="region-input" style="display:none;" onblur="this.form.submit()">
                </form>
                <form method="POST" action="{{ route('admin.countries.regions.destroy', [$country, $region]) }}" onsubmit="return confirm('Delete region?')">@csrf @method('DELETE')<button class="row-action"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button></form>
            </div>
        </div>
        @endforeach
        <form method="POST" action="{{ route('admin.countries.regions.store', $country) }}" class="add-region-form">
            @csrf
            <input name="name" placeholder="Add region..." required>
            <button class="button button-sm button-primary">Add</button>
        </form>
    </div>
</section>
@endforeach
<style>
.badge { background: var(--primary-light); color: var(--primary); padding: 0.15rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; }
.regions-list { display: flex; flex-direction: column; gap: 0.25rem; }
.region-item { display: flex; justify-content: space-between; align-items: center; padding: 0.4rem 0.75rem; background: var(--bg-subtle); border-radius: 0.375rem; font-size: 0.9rem; }
.add-region-form { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
.add-region-form input { flex: 1; padding: 0.4rem 0.75rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.85rem; }
.row-action { border: none; background: none; cursor: pointer; color: var(--text-muted); padding: 0.25rem; }
.row-action:hover { color: #ef4444; }
</style>
@endsection
