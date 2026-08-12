<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $quotation->reference }} - Safari Proposal</title>
    <style>
        @page { margin: 118px 68px 54px; size: A4 portrait; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #ffffff;
            color: #111111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.52;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .pdf-header {
            position: fixed;
            top: -96px;
            left: 0;
            right: 0;
            height: 88px;
            text-align: center;
            z-index: 5;
        }
        .pdf-header img {
            width: 178px;
            height: auto;
            display: block;
            margin: 0 auto 8px;
        }
        .tagline {
            font-family: "Times New Roman", Times, serif;
            font-style: italic;
            font-size: 11px;
            color: #111111;
        }
        .pdf-footer {
            position: fixed;
            bottom: -34px;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 5;
            font-family: "Times New Roman", Times, serif;
            font-style: italic;
            font-weight: bold;
            font-size: 16px;
            color: #111111;
        }
        .watermark {
            position: fixed;
            top: 255px;
            left: 162px;
            width: 290px;
            opacity: .10;
            z-index: 0;
        }
        .page {
            position: relative;
            z-index: 2;
            page-break-after: always;
            min-height: 650px;
        }
        .page:last-child { page-break-after: auto; }
        h1, h2, h3, h4 {
            font-family: "Times New Roman", Times, serif;
            color: #000000;
            margin: 0;
            font-weight: bold;
        }
        h1 {
            font-size: 25px;
            line-height: 1.35;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin: 14px 0 22px;
        }
        h2 {
            font-size: 25px;
            line-height: 1.25;
            text-align: center;
            margin: 20px 0 18px;
        }
        h3 {
            font-size: 18px;
            line-height: 1.35;
            margin: 24px 0 12px;
        }
        h4 {
            font-size: 15px;
            line-height: 1.35;
            margin: 16px 0 8px;
        }
        p { margin: 0 0 14px; }
        .serif-italic {
            font-family: "Times New Roman", Times, serif;
            font-style: italic;
            font-size: 16px;
            line-height: 1.55;
        }
        .center { text-align: center; }
        .muted { color: #333333; }
        .proposal-meta {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 24px;
            font-size: 12px;
        }
        .proposal-meta td {
            width: 25%;
            padding: 9px 10px;
            border-top: 1px solid #d9d9d9;
            border-bottom: 1px solid #d9d9d9;
            text-align: center;
        }
        .proposal-meta strong {
            display: block;
            font-family: "Times New Roman", Times, serif;
            font-size: 17px;
            color: #000;
        }
        .proposal-meta span {
            display: block;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: .9px;
            font-size: 8px;
        }
        .comparison-table,
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 22px;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .comparison-table th,
        .pricing-table th {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #111;
            text-align: left;
            padding: 8px 7px;
            border-bottom: 1px solid #111;
        }
        .comparison-table td,
        .pricing-table td {
            padding: 8px 7px;
            border-bottom: 1px solid #dddddd;
            vertical-align: top;
        }
        .total-row td {
            border-top: 1.5px solid #111;
            border-bottom: 1.5px solid #111;
            font-weight: bold;
            font-size: 12px;
        }
        .day-block {
            position: relative;
            z-index: 2;
            page-break-inside: avoid;
            margin-bottom: 22px;
        }
        .day-title {
            font-family: "Times New Roman", Times, serif;
            font-size: 18px;
            font-weight: bold;
            margin: 18px 0 12px;
        }
        .day-copy {
            font-size: 14px;
            line-height: 1.52;
            margin-bottom: 12px;
        }
        .photo-strip {
            width: 100%;
            max-height: 190px;
            object-fit: cover;
            margin: 10px 0 14px;
            border: 0;
        }
        .activity-list {
            margin: 8px 0 12px 18px;
            padding: 0;
        }
        .activity-list li {
            margin: 0 0 5px;
            padding-left: 4px;
        }
        .accommodation-box {
            border-top: 1px solid #d9d9d9;
            border-bottom: 1px solid #d9d9d9;
            padding: 10px 0;
            margin: 12px 0;
            font-size: 13px;
        }
        .two-col {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .two-col td {
            width: 50%;
            vertical-align: top;
            padding-right: 18px;
        }
        .two-col h4 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .two-col ul {
            margin: 0 0 0 16px;
            padding: 0;
            font-size: 12px;
        }
        .two-col li { margin-bottom: 5px; }
        .signature-box {
            border-top: 1px solid #111;
            border-bottom: 1px solid #111;
            padding: 16px 0 12px;
            margin-top: 24px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        .signature-table td {
            width: 50%;
            padding: 26px 18px 4px 0;
            border-bottom: 1px solid #111;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }
        .company {
            text-align: center;
            margin-top: 26px;
            font-size: 12px;
        }
        .company-name {
            font-family: "Times New Roman", Times, serif;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .small-note {
            font-size: 10px;
            color: #444;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('images/brand/shishi-footsteps-green.png');
    $pawPath = public_path('images/brand/shishi-paw-green.png');
    $logoData = file_exists($logoPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)) : '';
    $pawData = file_exists($pawPath) ? 'data:image/png;base64,'.base64_encode(file_get_contents($pawPath)) : '';
    $coverImg = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800';
    $destinations = $days->pluck('to_location')->unique()->filter()->implode(' - ');
    $firstDate = \Carbon\Carbon::parse($quotation->start_date);
    $lastDate = (clone $firstDate)->addDays($quotation->duration_days - 1);
    $guestCount = max(1, $quotation->guest_count);
    $hours = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'];
    $dayImgs = [
        'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800',
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
        'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800',
        'https://images.unsplash.com/photo-1536240478700-b869070f9279?w=800',
        'https://images.unsplash.com/photo-1518709766631-a6a7f45921c3?w=800',
    ];
@endphp

<div class="pdf-header">
    @if($logoData)<img src="{{ $logoData }}" alt="Shishi Footsteps">@endif
    <div class="tagline">Let's Go Somewhere, Anywhere, Everywhere.</div>
</div>
@if($pawData)<img class="watermark" src="{{ $pawData }}" alt="">@endif
<div class="pdf-footer">shishifootsteps.com</div>

<section class="page">
    <h1>{{ $quotation->title }}</h1>
    <p class="serif-italic">Dear {{ $quotation->client_name }},</p>
    <div class="serif-italic">
        <p>Thank you for considering us to create your dream African safari. We have carefully crafted this itinerary to showcase the very best of East Africa's wilderness, wildlife, and wonders.</p>
        <p>Every detail has been thoughtfully arranged to ensure your journey is seamless, luxurious, and unforgettable. From the moment you arrive until your final farewell, you will be immersed in the magic of Africa.</p>
        <p>We look forward to welcoming you to Africa.</p>
        <p>Warmest regards,</p>
        <p><strong>The Shishi Footsteps Team</strong></p>
        <p>Let's Go Somewhere, Anywhere, Everywhere.</p>
    </div>
    <table class="proposal-meta">
        <tr>
            <td><strong>{{ $quotation->duration_days }} Days</strong><span>Duration</span></td>
            <td><strong>{{ $guestCount }}</strong><span>Guests</span></td>
            <td><strong>{{ $firstDate->format('d M Y') }}</strong><span>Start</span></td>
            <td><strong>{{ $quotation->currency }} {{ number_format($grandTotal) }}</strong><span>Investment</span></td>
        </tr>
    </table>
    <p class="center small-note">Prepared exclusively for {{ $quotation->client_name }} - {{ $quotation->reference }}</p>
</section>

<section class="page">
    <h1>Compare at a glance the safari plan.</h1>
    <h2>{{ $destinations ?: 'East Africa' }}</h2>
    <p class="serif-italic center">{{ $firstDate->format('d M Y') }} - {{ $lastDate->format('d M Y') }}</p>
    <table class="comparison-table">
        <thead>
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Destination</th>
            <th>Accommodation</th>
            <th>Room Type</th>
            <th>Meal Plan</th>
            <th>Nights</th>
        </tr>
        </thead>
        <tbody>
        @foreach($days as $day)
            @php $rm = $day->items->where('item_type','room')->first(); @endphp
            <tr>
                <td>{{ $day->day_number }}</td>
                <td>{{ \Carbon\Carbon::parse($day->travel_date)->format('d M') }}</td>
                <td>{{ $day->to_location }}</td>
                <td>{{ $rm ? trim(explode('(', $rm->title)[0]) : '-' }}</td>
                <td>{{ $rm && str_contains($rm->title, '(') ? trim(explode('(', $rm->title)[1], ' )') : '-' }}</td>
                <td>Full Board</td>
                <td>1</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h3>Trip Highlights</h3>
    <ul class="activity-list">
        <li>Expert-guided safari drives in open 4x4 vehicles.</li>
        <li>Hand-selected accommodation as specified in the itinerary.</li>
        <li>Meals and travel arrangements as shown in the quotation.</li>
    </ul>
</section>

<section class="page">
@foreach($days as $idx => $day)
    @php
        $rm = $day->items->where('item_type','room')->first();
        $acts = $day->items->where('item_type','activity');
    @endphp
    <div class="day-block">
        <div class="day-title">Day {{ $day->day_number }} | {{ $day->to_location ?: 'Safari' }}</div>
        <p class="muted">{{ \Carbon\Carbon::parse($day->travel_date)->format('l, d F Y') }} - 1 Night</p>
        <img class="photo-strip" src="{{ $dayImgs[$idx % count($dayImgs)] }}" alt="{{ $day->to_location }}">
        @if($day->description)
            <div class="day-copy">{{ $day->description }}</div>
        @endif
        @if($acts->count())
            <h4>Activities</h4>
            <ul class="activity-list">
                @foreach($acts as $i => $act)
                    <li><strong>{{ $hours[$i % count($hours)] }}</strong> - {{ $act->title }}</li>
                @endforeach
            </ul>
        @endif
        @if($rm)
            <div class="accommodation-box">
                <strong>Accommodation:</strong> {{ $rm->title }}
                @if($rm->sell_total > 0)
                    <br><strong>Rate:</strong> {{ $quotation->currency }} {{ number_format($rm->sell_total, 2) }} per room
                @endif
            </div>
        @endif
    </div>
@endforeach
</section>

<section class="page">
    <h1>Your safari investment.</h1>
    <table class="proposal-meta">
        <tr>
            <td><strong>{{ $quotation->duration_days }} Days</strong><span>Tour Length</span></td>
            <td><strong>{{ $guestCount }}</strong><span>Travellers</span></td>
            <td><strong>{{ $firstDate->format('d M') }}</strong><span>Start Tour</span></td>
            <td><strong>{{ $lastDate->format('d M') }}</strong><span>End Tour</span></td>
        </tr>
    </table>
    <table class="pricing-table">
        <thead>
        <tr>
            <th>Day</th>
            <th>Service</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($days as $day)
            @foreach($day->items as $item)
                <tr>
                    <td>Day {{ $day->day_number }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $quotation->currency }} {{ number_format($item->sell_unit_price, 2) }}</td>
                    <td>{{ $quotation->currency }} {{ number_format($item->sell_total, 2) }}</td>
                </tr>
            @endforeach
        @endforeach
        @foreach($adjustments as $adj)
            <tr>
                <td colspan="2">{{ ucfirst($adj->type) }}: {{ $adj->description }}</td>
                <td>{{ $adj->quantity }}</td>
                <td>{{ $adj->currency }} {{ number_format($adj->unit_amount, 2) }}</td>
                <td>{{ $adj->currency }} {{ number_format($adj->unit_amount * $adj->quantity, 2) }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4">Grand Total</td>
            <td>{{ $quotation->currency }} {{ number_format($grandTotal, 2) }}</td>
        </tr>
        </tbody>
    </table>
    <table class="two-col">
        <tr>
            <td>
                <h4>Included</h4>
                <ul>
                    <li>All accommodation as specified</li>
                    <li>Professional guide and safari vehicle</li>
                    <li>Park and conservation fees</li>
                    <li>Meals as per itinerary</li>
                    <li>Airport transfers</li>
                    <li>Bottled water</li>
                </ul>
            </td>
            <td>
                <h4>Excluded</h4>
                <ul>
                    <li>International flights</li>
                    <li>Travel insurance</li>
                    <li>Visa fees</li>
                    <li>Personal expenses</li>
                    <li>Tips and gratuities</li>
                </ul>
            </td>
        </tr>
    </table>
</section>

<section class="page">
    <h1>Ready to confirm your safari?</h1>
    <p class="serif-italic center">This proposal is valid for 14 days from {{ date('d M Y') }}. To secure your booking, please sign and return this page or contact your safari consultant.</p>
    <div class="signature-box">
        <p>I accept the itinerary and pricing as outlined in this proposal (Ref: {{ $quotation->reference }}).</p>
        <table class="signature-table">
            <tr>
                <td>Signature</td>
                <td>Date</td>
            </tr>
        </table>
    </div>
    <div class="company">
        <div class="company-name">Shishi Footsteps</div>
        <p>Luxury African Safari Experiences</p>
        <p>Call or WhatsApp: +254 725 346 022</p>
        <p>General Inquiries: info@shishifootsteps.com</p>
        <p>Bookings: bookings@shishifootsteps.com</p>
        <p>Office: Nairobi, Kenya</p>
        <p class="small-note">Proposal {{ $quotation->reference }} - Generated {{ now()->format('d M Y') }}</p>
    </div>
</section>
</body>
</html>
