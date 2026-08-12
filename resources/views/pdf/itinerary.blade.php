<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }
        .cover-page { text-align: center; padding-top: 40%; }
        .cover-page h1 { font-size: 28px; color: #234A36; font-weight: 800; margin-bottom: 8px; }
        .cover-page h2 { font-size: 18px; color: #C8A96A; margin-bottom: 16px; }
        .cover-page p { font-size: 11px; color: #555; margin: 4px 0; }
        .section-title { font-size: 16px; font-weight: bold; color: #234A36; margin: 24px 0 12px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .day-section { page-break-inside: avoid; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }
        .day-number { font-size: 14px; font-weight: bold; color: #234A36; margin-bottom: 6px; }
        .label { font-weight: bold; color: #234A36; font-size: 9px; }
        .value { font-size: 9px; color: #555; margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; font-size: 9px; }
        th { background: #234A36; color: #fff; font-size: 9px; font-weight: bold; }
        .terms { font-size: 8px; color: #666; margin-top: 30px; }
        .terms h3 { font-size: 10px; color: #234A36; margin-bottom: 4px; margin-top: 12px; }
        .terms p { margin: 2px 0 8px; white-space: pre-wrap; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; }
        .page-break { page-break-before: always; }
        .activity-badge { display: inline; padding: 1px 5px; font-size: 8px; background: #ede8df; border-radius: 2px; margin-right: 3px; }
    </style>
</head>
<body>
    {{-- Cover Page --}}
    <div class="cover-page">
        <h1>{{ $template->trip_name ?? $template->name }}</h1>
        <h2>Luxury Safari Proposal</h2>
        <p><strong>Duration:</strong> {{ $template->duration_days }} Days</p>
        <p><strong>Category:</strong> {{ $categories[$template->category] ?? $template->category ?? 'Custom' }}</p>
        @if($template->destination)<p><strong>Destination:</strong> {{ $template->destination->name }}</p>@endif
    </div>

    {{-- Overview --}}
    <div class="page-break"></div>
    @if($template->overview)
    <h2 class="section-title">Overview</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap">{{ $template->overview }}</p>
    @endif

    @if($template->highlights)
    <h2 class="section-title">Highlights</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap">{{ $template->highlights }}</p>
    @endif

    @if($template->includes)
    <h2 class="section-title">Includes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap">{{ $template->includes }}</p>
    @endif

    @if($template->excludes)
    <h2 class="section-title">Excludes</h2>
    <p style="font-size:9px;color:#555;white-space:pre-wrap">{{ $template->excludes }}</p>
    @endif

    {{-- Day by Day Itinerary --}}
    <div class="page-break"></div>
    <h2 class="section-title">Your Safari Itinerary</h2>
    @foreach($template->days as $day)
    <div class="day-section">
        <div class="day-number">Day {{ $day->day_number }}: {{ $day->title ?? '' }}</div>
        @if($day->destination)
        <p class="value"><span class="label">Destination:</span> {{ $day->destination->name }}</p>
        @endif
        @if($day->hotel || $day->hotel_name)
        <p class="value"><span class="label">Accommodation:</span> {{ $day->hotel->name ?? $day->hotel_name }}@if($day->room_type) ({{ $day->room_type }})@endif</p>
        @endif
        @if($day->meal_plan)
        <p class="value"><span class="label">Meal Plan:</span> {{ $day->meal_plan }}</p>
        @endif
        @if($day->description)
        <p class="value" style="white-space:pre-wrap;margin-top:4px">{{ $day->description }}</p>
        @endif
        @if($day->morning_activity)
        <p class="value"><span class="label">Morning:</span> {{ $day->morning_activity }}</p>
        @endif
        @if($day->afternoon_activity)
        <p class="value"><span class="label">Afternoon:</span> {{ $day->afternoon_activity }}</p>
        @endif
        @if($day->evening_activity)
        <p class="value"><span class="label">Evening:</span> {{ $day->evening_activity }}</p>
        @endif
        @if($day->included_services)
        <p class="value"><span class="label">Included:</span> {{ $day->included_services }}</p>
        @endif
        @if($day->optional_activities)
        <p class="value"><span class="label">Optional:</span> {{ $day->optional_activities }}</p>
        @endif
        @if($day->activities->count())
        <p class="value">
            @foreach($day->activities as $act)
            <span class="activity-badge">{{ $act->activity_name ?? $act->activity->name ?? 'Activity' }}</span>
            @endforeach
        </p>
        @endif
    </div>
    @endforeach

    {{-- Pricing --}}
    @if($template->pricing->count())
    <div class="page-break"></div>
    <h2 class="section-title">Investment</h2>
    <table>
        <thead>
            <tr>
                <th>Currency</th>
                <th>Per Person</th>
                <th>Single Supplement</th>
                <th>Total Cost</th>
                @if($template->pricing->first()->notes)<th>Notes</th>@endif
            </tr>
        </thead>
        <tbody>
            @foreach($template->pricing as $price)
            <tr>
                <td>{{ $price->currency }}</td>
                <td>{{ number_format($price->price_per_person, 2) }}</td>
                <td>{{ number_format($price->single_supplement, 2) }}</td>
                <td>{{ number_format($price->total_cost, 2) }}</td>
                @if($template->pricing->first()->notes)<td>{{ $price->notes ?? '' }}</td>@endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Terms & Conditions --}}
    <div class="page-break"></div>
    <h2 class="section-title">Terms & Conditions</h2>
    <div class="terms">
        @if($template->booking_terms)
        <h3>Booking Terms</h3>
        <p>{{ $template->booking_terms }}</p>
        @endif
        @if($template->cancellation_policy)
        <h3>Cancellation Policy</h3>
        <p>{{ $template->cancellation_policy }}</p>
        @endif
        @if($template->payment_schedule)
        <h3>Payment Schedule</h3>
        <p>{{ $template->payment_schedule }}</p>
        @endif
        @if($template->refund_policy)
        <h3>Refund Policy</h3>
        <p>{{ $template->refund_policy }}</p>
        @endif
        @if($template->important_notes)
        <h3>Important Notes</h3>
        <p>{{ $template->important_notes }}</p>
        @endif
        @if($template->terms)
        <h3>Terms</h3>
        <p>{{ $template->terms }}</p>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Shishi Footsteps · Call or WhatsApp: +254 725 346 022</p>
        <p>info@shishifootsteps.com · bookings@shishifootsteps.com · Nairobi, Kenya</p>
    </div>
</body>
</html>
