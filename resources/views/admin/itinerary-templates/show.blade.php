@extends('layouts.admin')
@section('title', $template->name)
@section('content')
<x-admin.top-bar :title="$template->name" :search="false" :addButton="false">
    <x-slot:actions>
        <a href="{{ route('admin.itinerary-templates.edit', $template) }}" class="button button-primary"><i data-lucide="square-pen"></i> Edit</a>
        <form method="POST" action="{{ route('admin.itinerary-templates.duplicate', $template) }}" style="display:inline" onsubmit="return confirm('Duplicate this template?')">
            @csrf
            <button class="button button-secondary"><i data-lucide="copy"></i> Duplicate</button>
        </form>
        <a href="{{ route('admin.itinerary-templates.preview', $template) }}" target="_blank" class="button button-secondary"><i data-lucide="eye"></i> Preview</a>
        <a href="{{ route('admin.itinerary-templates.pdf', $template) }}" class="button button-secondary"><i data-lucide="file-text"></i> PDF</a>
        <form method="POST" action="{{ route('admin.itinerary-templates.destroy', $template) }}" style="display:inline" onsubmit="return confirm('Delete this template?')">
            @csrf @method('DELETE')
            <button class="button button-secondary" style="color:var(--text-muted)"><i data-lucide="trash-2"></i> Delete</button>
        </form>
        <a href="{{ route('admin.itinerary-templates.index') }}" class="button button-ghost"><i data-lucide="arrow-left"></i> Back</a>
    </x-slot:actions>
    <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
        @if($template->category)
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;background:#ede8df;color:#3a3530">
            {{ $categories[$template->category] ?? $template->category }}
        </span>
        @endif
        @if($template->status === 'active')
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#16a34a;background:#f0fdf4">Active</span>
        @elseif($template->status === 'inactive')
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#dc2626;background:#fef2f2">Inactive</span>
        @else
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#6b7280;background:#f3f4f6">Archived</span>
        @endif
        <span style="color:var(--text-muted);font-size:8px">{{ $template->duration_days }} days · {{ $template->days->count() }} day{{ $template->days->count() !== 1 ? 's' : '' }} defined</span>
    </div>
</x-admin.top-bar>

@include('admin.partials.flash')

{{-- Tabs --}}
<div style="display:flex;gap:0;border-bottom:1px solid var(--line);margin-bottom:16px">
    <button type="button" class="tab-btn active" data-tab="overview" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid #234A36;color:#234A36;cursor:pointer">Overview</button>
    <button type="button" class="tab-btn" data-tab="itinerary" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Itinerary</button>
    <button type="button" class="tab-btn" data-tab="pricing" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Pricing</button>
    <button type="button" class="tab-btn" data-tab="policies" style="padding:8px 16px;font-size:9px;font-weight:600;background:none;border:none;border-bottom:2px solid transparent;color:var(--text-muted);cursor:pointer">Policies</button>
</div>

{{-- Tab: Overview --}}
<div class="tab-content" id="tab-overview">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
        <div style="display:flex;flex-direction:column;gap:16px">
            @if($template->overview)
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Overview</h2></div>
                <div style="padding:16px">
                    <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->overview }}</p>
                </div>
            </section>
            @endif

            @if($template->highlights || $template->includes || $template->excludes)
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Details</h2></div>
                <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    @if($template->highlights)
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Highlights</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->highlights }}</p>
                    </div>
                    @endif
                    @if($template->includes)
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Includes</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->includes }}</p>
                    </div>
                    @endif
                    @if($template->excludes)
                    <div>
                        <h3 style="font-size:9px;font-weight:700;color:#234A36;margin:0 0 8px">Excludes</h3>
                        <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->excludes }}</p>
                    </div>
                    @endif
                </div>
            </section>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Details</h2></div>
                <div style="padding:16px;display:flex;flex-direction:column;gap:0;font-size:9px">
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Name</span>
                        <span style="color:var(--text);font-weight:600">{{ $template->name }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Trip Name</span>
                        <span style="color:var(--text)">{{ $template->trip_name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Destination</span>
                        <span style="color:var(--text)">{{ $template->destination->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Duration</span>
                        <span style="color:var(--text)">{{ $template->duration_days }} days</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Category</span>
                        <span style="color:var(--text)">{{ $template->category ? ($categories[$template->category] ?? $template->category) : '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                        <span style="color:var(--text-muted)">Status</span>
                        <span style="color:var(--text)">{{ ucfirst($template->status) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0">
                        <span style="color:var(--text-muted)">Created</span>
                        <span style="color:var(--text)">{{ $template->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </section>

            @if($template->notes)
            <section class="ops-panel">
                <div class="ops-panel-title"><h2>Notes</h2></div>
                <div style="padding:16px">
                    <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->notes }}</p>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>

{{-- Tab: Itinerary --}}
<div class="tab-content" id="tab-itinerary" style="display:none">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Day-by-Day Itinerary</h2></div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:0">
            @forelse($template->days as $day)
            <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--line);{{ $loop->last ? 'border-bottom:0' : '' }}">
                <div style="display:flex;flex-direction:column;align-items:center;min-width:40px">
                    <span style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:#234A36;color:#fff;font-size:9px;font-weight:700">{{ $day->day_number }}</span>
                    @if(!$loop->last)
                    <div style="width:1px;flex:1;background:var(--line);margin-top:6px"></div>
                    @endif
                </div>
                <div style="flex:1">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <h3 style="margin:0;font-size:9px;font-weight:700;color:var(--text)">{{ $day->title ?? 'Day ' . $day->day_number }}</h3>
                        @if($day->destination)
                        <span style="font-size:8px;color:var(--text-muted)">{{ $day->destination->name ?? '' }}</span>
                        @endif
                    </div>
                    @if($day->hotel || $day->hotel_name)
                    <div style="display:flex;gap:12px;margin-bottom:6px">
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Accommodation:</strong> {{ $day->hotel->name ?? $day->hotel_name }}</span>
                        @if($day->room_type)
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Room:</strong> {{ $day->room_type }}</span>
                        @endif
                        @if($day->meal_plan)
                        <span style="font-size:8px;color:var(--text-muted)"><strong>Meals:</strong> {{ $day->meal_plan }}</span>
                        @endif
                    </div>
                    @endif
                    @if($day->morning_activity || $day->afternoon_activity || $day->evening_activity)
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:6px">
                        @if($day->morning_activity)
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Morning: {{ $day->morning_activity }}</span>
                        @endif
                        @if($day->afternoon_activity)
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Afternoon: {{ $day->afternoon_activity }}</span>
                        @endif
                        @if($day->evening_activity)
                        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:600;background:#ede8df;color:#3a3530">Evening: {{ $day->evening_activity }}</span>
                        @endif
                    </div>
                    @endif
                    @if($day->activities->count())
                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:6px">
                        @foreach($day->activities as $act)
                        <span style="display:inline-flex;padding:2px 6px;border-radius:3px;font-size:7px;background:#234A36;color:#fff">{{ $act->activity_name ?? $act->activity->name ?? 'Activity' }}</span>
                        @endforeach
                    </div>
                    @endif
                    @if($day->description)
                    <p style="margin:0;font-size:8px;color:var(--text-muted);white-space:pre-wrap">{{ $day->description }}</p>
                    @endif
                    @if($day->included_services)
                    <p style="margin:4px 0 0;font-size:8px;color:#16a34a"><strong>Included:</strong> {{ $day->included_services }}</p>
                    @endif
                    @if($day->optional_activities)
                    <p style="margin:4px 0 0;font-size:8px;color:#ca8a04"><strong>Optional:</strong> {{ $day->optional_activities }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p style="color:var(--text-muted);font-size:9px;text-align:center;padding:16px 0">No days defined yet.</p>
            @endforelse
        </div>
    </section>
</div>

{{-- Tab: Pricing --}}
<div class="tab-content" id="tab-pricing" style="display:none">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Pricing</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Price Per Person</th>
                        <th>Single Supplement</th>
                        <th>Total Cost</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($template->pricing as $price)
                    <tr>
                        <td style="font-size:9px">{{ $price->currency }}</td>
                        <td style="font-size:9px">{{ number_format($price->price_per_person, 2) }}</td>
                        <td style="font-size:9px">{{ number_format($price->single_supplement, 2) }}</td>
                        <td style="font-size:9px">{{ number_format($price->total_cost, 2) }}</td>
                        <td style="font-size:9px;color:var(--text-muted)">{{ $price->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px 16px;color:var(--text-muted);font-size:9px">No pricing defined.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Tab: Policies --}}
<div class="tab-content" id="tab-policies" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        @if($template->terms)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Terms</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->terms }}</p>
            </div>
        </section>
        @endif
        @if($template->booking_terms)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Booking Terms</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->booking_terms }}</p>
            </div>
        </section>
        @endif
        @if($template->payment_schedule)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Payment Schedule</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->payment_schedule }}</p>
            </div>
        </section>
        @endif
        @if($template->cancellation_policy)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Cancellation Policy</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->cancellation_policy }}</p>
            </div>
        </section>
        @endif
        @if($template->refund_policy)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Refund Policy</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->refund_policy }}</p>
            </div>
        </section>
        @endif
        @if($template->important_notes)
        <section class="ops-panel">
            <div class="ops-panel-title"><h2>Important Notes</h2></div>
            <div style="padding:16px">
                <p style="margin:0;font-size:9px;color:var(--text);white-space:pre-wrap">{{ $template->important_notes }}</p>
            </div>
        </section>
        @endif
        @if(!$template->terms && !$template->booking_terms && !$template->payment_schedule && !$template->cancellation_policy && !$template->refund_policy && !$template->important_notes)
        <section class="ops-panel" style="grid-column:1/-1">
            <div style="padding:16px;text-align:center;color:var(--text-muted);font-size:9px">No policies defined.</div>
        </section>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var target = this.dataset.tab;
            tabs.forEach(function(t) {
                t.style.color = 'var(--text-muted)';
                t.style.borderBottomColor = 'transparent';
            });
            this.style.color = '#234A36';
            this.style.borderBottomColor = '#234A36';
            document.querySelectorAll('.tab-content').forEach(function(c) {
                c.style.display = 'none';
            });
            document.getElementById('tab-' + target).style.display = '';
        });
    });
});
</script>
@endpush
@endsection
