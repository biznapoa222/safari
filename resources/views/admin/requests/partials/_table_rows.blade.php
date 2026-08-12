@forelse($requests as $request)
@php
    $clientName = trim($request->client_name ?: ($request->client?->name ?? ''));
    $parts = preg_split('/\s+/', $clientName, 2);
    $firstName = $parts[0] ?? 'N/A';
    $lastName = $parts[1] ?? '';
    $company = $request->company ?: 'Tanzania Specialist';
    $value = $request->quote_value ?: $request->budget;
    $language = strtoupper($request->language ?: 'EN');
    $responsible = $request->assignedUser?->name ?: $request->consultant?->name ?: 'NO USER';
    $colors = ['#ef1b1b', '#111111', '#62c7d7', '#12e62b', '#8b31c6', '#ff4aa2'];
    $dotColor = $colors[$loop->index % count($colors)];
@endphp
<tr>
    <td>{{ $loop->iteration + (($requests->currentPage() - 1) * $requests->perPage()) }}</td>
    <td>
        @if($request->followups->isNotEmpty())
            {{ $request->followups->sortBy('followup_date')->first()->followup_date?->format('d-m-Y') }}
        @endif
    </td>
    <td>{{ $request->arrival_date?->format('d-m-Y') }}</td>
    <td>{{ $request->created_at?->format('d-m-Y') }}</td>
    <td>{{ $firstName }}</td>
    <td>{{ $lastName }}</td>
    <td>{{ $request->status_label ?: 'New' }}</td>
    <td>{{ $value ? (($request->currency ?: '$').number_format($value, 2)) : '$0.00' }}</td>
    <td>
        <div class="pm-stars star-rating" data-request-id="{{ $request->id }}">
            @for($i = 1; $i <= 3; $i++)
                <button type="button" data-rating="{{ $i }}" class="star {{ $i <= ($request->rating ?? 1) ? 'is-on' : '' }}"><i data-lucide="star"></i></button>
            @endfor
        </div>
    </td>
    <td><button type="button" class="pm-flag-button" data-notes-trigger="{{ $request->id }}">FLAG THIS</button></td>
    <td><span class="pm-no-pill">{{ $request->is_diamond ? 'Yes' : 'No' }}</span></td>
    <td>{{ ucwords($request->source ?: 'Manual') }}</td>
    <td>{{ $language }}</td>
    <td><span>{{ $responsible }}</span><span class="pm-user-dot" style="background:{{ $dotColor }}"></span></td>
    <td>{{ $company }}</td>
    <td>
        <details class="pm-row-menu">
            <summary><i data-lucide="menu"></i></summary>
            <div>
                <a href="{{ route('admin.requests.show', $request->id) }}"><i data-lucide="arrow-right"></i> Open Request</a>
                <a href="{{ route('admin.requests.edit', $request->id) }}"><i data-lucide="pencil"></i> Edit request status</a>
                <a href="{{ route('admin.requests.show', $request->id) }}"><i data-lucide="info"></i> View Info</a>
                <a href="{{ route('admin.requests.show', $request->id) }}#timeline"><i data-lucide="clock"></i> View activity log</a>
                @if($request->status !== 'converted' && $request->status !== 'cancelled')
                    <button type="button" data-convert-quote="{{ $request->id }}"><i data-lucide="pencil"></i> Force change status</button>
                @endif
            </div>
        </details>
    </td>
</tr>
@empty
<tr>
    <td colspan="16" class="empty-cell">No requests found.</td>
</tr>
@endforelse
