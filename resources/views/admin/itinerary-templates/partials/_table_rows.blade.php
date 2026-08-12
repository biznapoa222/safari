@forelse($templates as $template)
<tr>
    <td>
        <a href="{{ route('admin.itinerary-templates.show', $template) }}" style="color:var(--primary);text-decoration:none;font-weight:600;font-size:9px">
            {{ $template->name }}
        </a>
        @if($template->trip_name)
            <small style="display:block;color:var(--text-muted);font-size:8px">{{ $template->trip_name }}</small>
        @endif
    </td>
    <td style="font-size:9px;color:var(--text)">{{ $template->destination->name ?? '—' }}</td>
    <td style="font-size:9px;color:var(--text)">{{ $template->duration_days }} days</td>
    <td>
        @if($template->category)
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;background:#ede8df;color:#3a3530">
            {{ \App\Models\ItineraryTemplate::categories()[$template->category] ?? $template->category }}
        </span>
        @else
        <span style="color:var(--text-muted);font-size:9px">—</span>
        @endif
    </td>
    <td>
        @if($template->status === 'active')
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#16a34a;background:#f0fdf4">Active</span>
        @elseif($template->status === 'inactive')
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#dc2626;background:#fef2f2">Inactive</span>
        @else
        <span style="display:inline-flex;padding:3px 7px;border-radius:4px;font-size:7px;font-weight:700;color:#6b7280;background:#f3f4f6">Archived</span>
        @endif
    </td>
    <td style="font-size:9px;color:var(--text)">{{ $template->days_count }}</td>
    <td>
        <div style="display:flex;gap:6px">
            <a href="{{ route('admin.itinerary-templates.show', $template) }}" class="icon-button" title="View">
                <i data-lucide="eye" style="width:13px;height:13px"></i>
            </a>
            <a href="{{ route('admin.itinerary-templates.edit', $template) }}" class="icon-button" title="Edit">
                <i data-lucide="square-pen" style="width:13px;height:13px"></i>
            </a>
            <form method="POST" action="{{ route('admin.itinerary-templates.duplicate', $template) }}" style="display:inline" onsubmit="return confirm('Duplicate this template?')">
                @csrf
                <button type="submit" class="icon-button" title="Duplicate">
                    <i data-lucide="copy" style="width:13px;height:13px"></i>
                </button>
            </form>
            <form method="POST" action="{{ route('admin.itinerary-templates.destroy', $template) }}" style="display:inline" onsubmit="return confirm('Delete this template?')">
                @csrf @method('DELETE')
                <button type="submit" class="icon-button" title="Delete">
                    <i data-lucide="trash-2" style="width:13px;height:13px"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center;padding:32px 16px;color:var(--text-muted);font-size:9px">No itinerary templates match the current filters.</td>
</tr>
@endforelse
