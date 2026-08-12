<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $template->trip_name ?? $template->name }} - Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', 'Outfit', sans-serif; font-size: 10px; color: #333; background: #fff; padding: 40px; }
        .cover { text-align: center; padding: 120px 0 60px; border-bottom: 2px solid #234A36; margin-bottom: 40px; }
        .cover h1 { font-family: 'Outfit', sans-serif; font-size: 28px; color: #234A36; font-weight: 800; margin-bottom: 8px; }
        .cover h2 { font-family: 'Great Vibes', cursive; font-size: 22px; color: #C8A96A; font-weight: 400; margin-bottom: 16px; }
        .cover .meta { font-size: 10px; color: #666; }
        .section-title { font-family: 'Outfit', sans-serif; font-size: 16px; color: #234A36; font-weight: 700; margin: 30px 0 16px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .day-block { page-break-inside: avoid; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #eee; }
        .day-number { font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; color: #234A36; margin-bottom: 6px; }
        .day-label { font-size: 9px; color: #234A36; font-weight: 600; }
        .day-value { font-size: 9px; color: #555; margin-bottom: 4px; }
        .activity-tag { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 8px; background: #ede8df; color: #3a3530; margin-right: 4px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; font-size: 9px; }
        th { background: #234A36; color: #fff; font-weight: 600; }
        .policy-section { margin-bottom: 20px; }
        .policy-section h3 { font-size: 10px; color: #234A36; font-weight: 700; margin-bottom: 6px; }
        .policy-section p { font-size: 9px; color: #555; white-space: pre-wrap; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; }
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 8px 20px; background: #234A36; color: #fff; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; }
        .print-btn:hover { background: #1a3829; }
        @media print { .print-btn { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print</button>

    <div class="cover">
        <h1>{{ $template->trip_name ?? $template->name }}</h1>
        <h2>Luxury Safari Proposal</h2>
        <div class="meta">
            <p>Duration: {{ $template->duration_days }} Days</p>
            <p>Category: {{ $categories[$template->category] ?? $template->category ?? 'Custom' }}</p>
            @if($template->destination)
            <p>Destination: {{ $template->destination->name }}</p>
            @endif
        </div>
    </div>

    @if($template->overview)
    <h2 class="section-title">Overview</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap;margin-bottom:20px">{{ $template->overview }}</p>
    @endif

    @if($template->highlights)
    <h2 class="section-title">Highlights</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap;margin-bottom:20px">{{ $template->highlights }}</p>
    @endif

    @if($template->includes)
    <h2 class="section-title">Includes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap;margin-bottom:20px">{{ $template->includes }}</p>
    @endif

    @if($template->excludes)
    <h2 class="section-title">Excludes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap;margin-bottom:20px">{{ $template->excludes }}</p>
    @endif

    <h2 class="section-title">Your Safari Itinerary</h2>
    @foreach($template->days as $day)
    <div class="day-block">
        <div class="day-number">Day {{ $day->day_number }}: {{ $day->title ?? '' }}</div>
        @if($day->destination)
        <div><span class="day-label">Destination:</span> <span class="day-value">{{ $day->destination->name }}</span></div>
        @endif
        @if($day->hotel || $day->hotel_name)
        <div><span class="day-label">Accommodation:</span> <span class="day-value">{{ $day->hotel->name ?? $day->hotel_name }}@if($day->room_type) ({{ $day->room_type }})@endif</span></div>
        @endif
        @if($day->meal_plan)
        <div><span class="day-label">Meal Plan:</span> <span class="day-value">{{ $day->meal_plan }}</span></div>
        @endif
        @if($day->description)
        <p style="font-size:9px;color:#555;white-space:pre-wrap;margin:6px 0">{{ $day->description }}</p>
        @endif
        @if($day->morning_activity)
        <div><span class="day-label">Morning:</span> <span class="day-value">{{ $day->morning_activity }}</span></div>
        @endif
        @if($day->afternoon_activity)
        <div><span class="day-label">Afternoon:</span> <span class="day-value">{{ $day->afternoon_activity }}</span></div>
        @endif
        @if($day->evening_activity)
        <div><span class="day-label">Evening:</span> <span class="day-value">{{ $day->evening_activity }}</span></div>
        @endif
        @if($day->included_services)
        <div style="margin-top:4px"><span class="day-label">Included Services:</span> <span class="day-value">{{ $day->included_services }}</span></div>
        @endif
        @if($day->optional_activities)
        <div><span class="day-label">Optional:</span> <span class="day-value">{{ $day->optional_activities }}</span></div>
        @endif
        @if($day->activities->count())
        <div style="margin-top:4px">
            @foreach($day->activities as $act)
            <span class="activity-tag">{{ $act->activity_name ?? $act->activity->name ?? 'Activity' }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach

    @if($template->pricing->count())
    <h2 class="section-title">Investment</h2>
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Per Person</th>
                <th>Single Supplement</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($template->pricing as $price)
            <tr>
                <td>{{ $price->currency }}</td>
                <td>{{ number_format($price->price_per_person, 2) }}</td>
                <td>{{ number_format($price->single_supplement, 2) }}</td>
                <td>{{ number_format($price->total_cost, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2 class="section-title">Terms & Conditions</h2>
    @if($template->booking_terms)
    <div class="policy-section">
        <h3>Booking Terms</h3>
        <p>{{ $template->booking_terms }}</p>
    </div>
    @endif
    @if($template->cancellation_policy)
    <div class="policy-section">
        <h3>Cancellation Policy</h3>
        <p>{{ $template->cancellation_policy }}</p>
    </div>
    @endif
    @if($template->payment_schedule)
    <div class="policy-section">
        <h3>Payment Schedule</h3>
        <p>{{ $template->payment_schedule }}</p>
    </div>
    @endif
    @if($template->important_notes)
    <div class="policy-section">
        <h3>Important Notes</h3>
        <p>{{ $template->important_notes }}</p>
    </div>
    @endif
    @if($template->terms)
    <div class="policy-section">
        <h3>Terms</h3>
        <p>{{ $template->terms }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Generated by Shishi Footsteps Safari ERP</p>
    </div>
</body>
</html>
