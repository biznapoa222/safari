@extends('layouts.admin')
@section('title', 'Users & Roles')

@section('content')
<x-admin.top-bar
    title="Users & Roles"
    description="System Settings"
    addLabel="Add User"
    addOnclick="document.querySelector('[data-user-form]').hidden = !document.querySelector('[data-user-form]').hidden"
    :search="false"
/>
@include('admin.partials.flash')

<section class="user-create-panel ops-panel" data-user-form hidden>
    <div class="ops-panel-title"><div><h2>Create team account</h2><p>Assign the correct department role and a temporary password.</p></div><button class="popover-close user-form-close" type="button" data-user-form-toggle><i data-lucide="x"></i></button></div>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="ops-form-grid">
            <label>Full name<input name="name" required></label>
            <label>Email address<input type="email" name="email" required></label>
            <label>Role<select name="role">@foreach($roles as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
            <label>Department<input name="department" placeholder="Sales, Operations, Finance..."></label>
            <label>Phone<input name="phone" placeholder="+254 ..."></label>
            <label>Temporary password<input type="password" name="password" minlength="8" required></label>
            <label class="check-label"><input type="checkbox" name="is_active" value="1" checked> Active account</label>
        </div>
        <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="user-plus"></i>Create user</button></div>
    </form>
</section>

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Search name, email or department"></label>
        <select name="role"><option value="">All roles</option>@foreach($roles as $value => $label)<option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>@endforeach</select>
        <button class="button button-primary">Search</button><a class="button button-secondary" href="{{ route('admin.users.index') }}">Reset</a>
    </form>
    <div class="user-directory">
        @foreach($users as $user)
            <article class="user-card">
                <div class="user-avatar">{{ $user->initials() }}</div>
                <div class="user-identity"><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p><span>{{ $user->department ?: 'General administration' }}</span></div>
                <div class="user-role"><small>Access role</small><strong>{{ $roles[$user->role] ?? ucfirst($user->role) }}</strong></div>
                <div class="user-activity"><small>Last sign in</small><strong>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</strong></div>
                <span class="user-state {{ $user->is_active ? 'is-active' : '' }}"><i></i>{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                <details class="edit-popover">
                    <summary><i data-lucide="square-pen"></i>Edit</summary>
                    <div class="edit-popover-panel">
                        <header><div><small>Edit user account</small><strong>{{ $user->name }}</strong></div><button type="button" class="popover-close"><i data-lucide="x"></i></button></header>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf @method('PUT')
                            <input name="name" value="{{ $user->name }}" placeholder="Full name" required>
                            <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
                            <select name="role">@foreach($roles as $value => $label)<option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>@endforeach</select>
                            <input name="department" value="{{ $user->department }}" placeholder="Department">
                            <input name="phone" value="{{ $user->phone }}" placeholder="Phone">
                            <input type="password" name="password" minlength="8" placeholder="New password (leave blank to keep)">
                            <label class="check-label"><input type="checkbox" name="is_active" value="1" @checked($user->is_active)> Active account</label>
                            <button class="button button-primary">Save changes</button>
                        </form>
                        @if(!auth()->user()->is($user))<form class="popover-delete-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user account?')">@csrf @method('DELETE')<button class="button danger-button">Delete user</button></form>@endif
                    </div>
                </details>
            </article>
        @endforeach
    </div>
    <div class="ops-pagination">{{ $users->links() }}</div>
</section>
@endsection
