@extends('layouts.admin')
@section('title', 'Supplier Report')
@section('content')

<x-admin.top-bar
    title="Supplier Report"
    description="Reports"
    :addButton="false"
    :search="false"
/>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Suppliers by Type</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Type</th><th>Count</th></tr></thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td>{{ \App\Models\Supplier::$types[$d->type] ?? $d->type }}</td>
                    <td><strong>{{ $d->total }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center text-muted">No suppliers.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
