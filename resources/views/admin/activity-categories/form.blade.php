@extends('layouts.admin')
@section('title', $category ? 'Edit Category' : 'New Category')
@section('content')
<div class="page-heading">
    <div><p class="eyebrow">Activity Categories</p><h1>{{ $category ? 'Edit: '.$category->name : 'New Category' }}</h1></div>
</div>
@include('admin.partials.flash')
<form method="POST" action="{{ $category ? route('admin.activity-categories.update', $category) : route('admin.activity-categories.store') }}" class="ops-panel" style="max-width:500px;">
    @csrf @if($category) @method('PUT') @endif
    <div class="ops-form-grid" style="display:flex;flex-direction:column;gap:1rem;">
        <label>Name<input name="name" value="{{ old('name', $category->name ?? '') }}" required></label>
        <label>Description<textarea name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea></label>
    </div>
    <div class="ops-form-footer">
        <a href="{{ route('admin.activity-categories.index') }}" class="button button-secondary">Cancel</a>
        <button class="button button-primary"><i data-lucide="save"></i>{{ $category ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
