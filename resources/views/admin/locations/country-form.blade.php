@extends('layouts.admin')
@section('title', $country ? 'Edit Country' : 'New Country')
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Location Management</p><h1>{{ $country ? 'Edit: '.$country->name : 'New Country' }}</h1></div>
</div>
@include('admin.partials.flash')
<form method="POST" action="{{ $country ? route('admin.countries.update', $country) : route('admin.countries.store') }}" class="ops-panel" style="max-width:500px;">
    @csrf @if($country) @method('PUT') @endif
    <div class="ops-form-grid" style="display:flex;flex-direction:column;gap:1rem;">
        <label>Country Code (3 letters)<input name="code" value="{{ old('code', $country->code ?? '') }}" maxlength="3" required></label>
        <label>Country Name<input name="name" value="{{ old('name', $country->name ?? '') }}" required></label>
    </div>
    <div class="ops-form-footer">
        <a href="{{ route('admin.countries.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $country ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
