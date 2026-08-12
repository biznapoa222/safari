@extends('layouts.admin')
@section('title', 'Activity Categories')
@section('content')
<x-admin.top-bar
    title="Activity Categories"
    description="Activities"
    addLabel="New Category"
    addRoute="{{ route('admin.activity-categories.create') }}"
    :search="false"
/>
@include('admin.partials.flash')
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Activities</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td><strong>{{ $cat->name }}</strong></td>
                <td>{{ $cat->slug }}</td>
                <td>{{ $cat->activities->count() }}</td>
                <td>
                    <a href="{{ route('admin.activity-categories.edit', $cat) }}" class="button button-sm button-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.activity-categories.destroy', $cat) }}" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="button button-sm button-danger">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted">No categories.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $categories->links() }}</div>
@endsection
