@extends('layouts.admin')
@section('title', 'Website Requests')
@section('content')
<x-admin.top-bar
    title="Leads"
    description="CRM"
    :addButton="false"
    searchPlaceholder="Search leads..."
/>
@include('admin.partials.flash')
<section class="pipeline-summary">
    @foreach(['new','assigned','contacted','qualified','quotation','won','on_trip','completed'] as $stage)
        <a href="{{ route('admin.legacy-leads.index', ['status' => $stage]) }}"><span>{{ DB::table('website_enquiries')->where('lifecycle_status', $stage)->count() }}</span><small>{{ ucwords(str_replace('_', ' ', $stage)) }}</small></a>
    @endforeach
</section>
<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <select name="status"><option value="">All pipeline stages</option>@foreach(['new','assigned','contacted','qualified','quotation','won','lost','on_trip','completed'] as $stage)<option value="{{ $stage }}" @selected(request('status') === $stage)>{{ ucwords(str_replace('_', ' ', $stage)) }}</option>@endforeach</select>
        <select name="assigned_to"><option value="">All owners</option>@foreach($users as $user)<option @selected(request('assigned_to') === $user->name)>{{ $user->name }}</option>@endforeach</select>
        <button class="button button-primary">Filter</button>
    </form>
    <div class="table-wrap"><table class="ops-table leads-table"><thead><tr><th>Request</th><th>Travel plan</th><th>Source</th><th>Owner & next action</th><th>Stage</th><th>Value</th><th>Actions</th></tr></thead><tbody>
    @forelse($leads as $lead)
        <tr>
            <td><strong>{{ $lead->name }}</strong><small>{{ $lead->email }} · {{ $lead->country }}</small><p>{{ \Illuminate\Support\Str::limit($lead->message, 90) }}</p></td>
            <td>{{ $lead->destination ?: 'Tailor-made' }}<small>{{ $lead->travelers }} travelers · {{ $lead->travel_date ? \Carbon\Carbon::parse($lead->travel_date)->format('d M Y') : 'Flexible dates' }}</small></td>
            <td><span class="ops-pill ops-pill--blue">{{ ucfirst($lead->source) }}</span><small>{{ strtoupper($lead->language_code) }}</small></td>
            <td>
                <form class="lead-update-form" method="POST" action="{{ route('admin.legacy-leads.update', $lead->id) }}">@csrf @method('PUT')
                    <select name="assigned_to"><option value="">Unassigned</option>@foreach($users as $user)<option @selected($lead->assigned_to === $user->name)>{{ $user->name }}</option>@endforeach</select>
                    <input type="datetime-local" name="next_follow_up_at" value="{{ $lead->next_follow_up_at ? \Carbon\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d\TH:i') : '' }}">
            </td>
            <td><select name="lifecycle_status">@foreach(['new','assigned','contacted','qualified','quotation','won','lost','on_trip','completed'] as $stage)<option value="{{ $stage }}" @selected($lead->lifecycle_status === $stage)>{{ ucwords(str_replace('_', ' ', $stage)) }}</option>@endforeach</select></td>
            <td><input class="money-input" type="number" step="0.01" name="estimated_value" value="{{ $lead->estimated_value }}"><input type="hidden" name="message" value="{{ $lead->message }}"></td>
            <td><div class="ops-actions"><button title="Save follow-up"><i data-lucide="save"></i></button></form>@if(!$lead->converted_quotation_id)<form method="POST" action="{{ route('admin.legacy-leads.convert', $lead->id) }}">@csrf<button class="convert-button" title="Create quotation"><i data-lucide="file-plus-2"></i></button></form>@else<a href="{{ route('admin.quotations.show', $lead->converted_quotation_id) }}" title="Open quotation"><i data-lucide="external-link"></i></a>@endif</div></td>
        </tr>
    @empty<tr><td colspan="7" class="empty-cell">No requests in this stage.</td></tr>@endforelse
    </tbody></table></div><div class="ops-pagination">{{ $leads->links() }}</div>
</section>
@endsection
