@extends('layouts.admin')
@section('title', 'Website CMS')
@section('content')
<x-admin.top-bar
    title="Website Pages"
    description="Content Management"
    addLabel="New Page"
    addRoute="{{ route('admin.cms.pages.create') }}"
    :search="false"
/>
@include('admin.partials.flash')
<div class="ops-actions-bar">
    <a href="{{ route('admin.cms.home-settings') }}" class="button button-secondary"><i data-lucide="settings-2"></i>Homepage Settings</a>
    <a href="{{ route('home') }}" target="_blank" class="button button-secondary"><i data-lucide="external-link"></i>View Website</a>
</div>
<div class="ops-panel" style="margin-bottom:24px;padding:20px"><h2 style="margin-top:0">Website content sections</h2><p>Edit shared contact details and the text/images used by each main public page.</p><div style="display:flex;flex-wrap:wrap;gap:10px">@foreach(config('cms.pages') as $section => $definition)<a class="button button-secondary" href="{{ route('admin.cms.content.edit', $section) }}">{{ $definition['label'] }}</a>@endforeach</div></div>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>Title</th><th>Type</th><th>Slug</th><th>Published</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($pages as $page)
            <tr>
                <td><strong>{{ $page->title }}</strong></td>
                <td><span class="status">{{ ucfirst($page->type) }}</span></td>
                <td><small>{{ $page->slug }}</small></td>
                <td>
                    <form method="POST" action="{{ route('admin.cms.pages.publish', $page) }}" style="display:inline;">
                        @csrf
                        <button style="border:none;background:none;cursor:pointer;">
                            @if($page->published)<span class="text-green">Published</span>@else<span class="text-red">Draft</span>@endif
                        </button>
                    </form>
                </td>
                <td><small>{{ $page->updated_at->format('d/m/Y') }}</small></td>
                <td>
                    <div class="ops-actions">
                        <a href="{{ route('admin.cms.pages.edit', $page) }}"><i data-lucide="square-pen"></i></a>
                        <form method="POST" action="{{ route('admin.cms.pages.destroy', $page) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button><i data-lucide="trash-2"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No pages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $pages->links() }}</div>
<style>.text-green { color: #22c55e; } .text-red { color: #ef4444; } .ops-actions { display: flex; gap: 0.25rem; } .ops-actions a, .ops-actions button { padding: 0.25rem; border: none; background: none; cursor: pointer; color: var(--text-muted); } .ops-actions a:hover, .ops-actions button:hover { color: var(--primary); }</style>
@endsection
