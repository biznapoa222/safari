@extends('layouts.admin')
@section('title', 'Activity Report')
@section('content')

<x-admin.top-bar
    title="Activity Report"
    description="Reports"
    :addButton="false"
    :search="false"
/>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Most Booked Activities</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Activity</th><th>Country</th><th>Location</th><th>Bookings</th></tr></thead>
            <tbody>
                @forelse($activities as $a)
                <tr>
                    <td><strong>{{ $a->name }}</strong></td>
                    <td>{{ $a->country }}</td>
                    <td>{{ $a->location }}</td>
                    <td><strong>{{ $a->booking_count }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">No activity data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $activities->links() }}</div>
</section>
@endsection
