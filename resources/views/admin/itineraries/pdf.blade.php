<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin: 28px 34px 42px; }
* { box-sizing: border-box; }
body { margin: 0; color: #283a33; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.55; }
.cover { position: relative; height: 690px; margin: -28px -34px 0; color: white; background: #173e32; page-break-after: always; overflow: hidden; }
.cover img { width: 100%; height: 690px; object-fit: cover; opacity: .62; }
.cover-copy { position: absolute; left: 45px; right: 45px; bottom: 62px; }
.kicker { color: #d9bb6b; font-size: 9px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; }
h1 { margin: 12px 0 14px; font-family: DejaVu Serif, serif; font-size: 38px; line-height: 1.12; }
.cover p { width: 82%; margin: 0; font-size: 12px; line-height: 1.7; }
.facts { width: 100%; margin: 0 0 25px; border-collapse: collapse; background: #f2f5ef; }
.facts td { width: 25%; padding: 13px; border-right: 1px solid #dce4dc; vertical-align: top; }
.facts small, .meta small { display: block; color: #87938e; font-size: 7px; text-transform: uppercase; }
.facts strong { display: block; margin-top: 4px; font-size: 9px; }
h2 { margin: 0 0 8px; color: #173e32; font-family: DejaVu Serif, serif; font-size: 21px; }
.intro { margin: 0 0 28px; }
.intro p { font-size: 10px; }
.day { margin: 0 0 22px; padding-bottom: 20px; border-bottom: 1px solid #dce3df; page-break-inside: avoid; }
.day-number { width: 58px; padding-top: 3px; float: left; color: #9a7434; font-size: 8px; text-transform: uppercase; }
.day-number b { display: block; color: #173e32; font-family: DejaVu Serif, serif; font-size: 27px; }
.day-body { margin-left: 68px; }
.day-body > span { color: #9a7434; font-size: 7px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
.day h3 { margin: 4px 0 8px; color: #173e32; font-family: DejaVu Serif, serif; font-size: 17px; }
.day-image { width: 100%; height: 220px; margin: 4px 0 11px; object-fit: cover; }
.lead { font-weight: bold; }
.activities { margin: 9px 0; padding: 8px 11px; background: #f2f5ef; }
.activities span { display: block; }
.meta { width: 100%; margin-top: 10px; border-collapse: collapse; }
.meta td { padding: 7px 8px; border: 1px solid #dce3df; vertical-align: top; }
.clear { clear: both; }
.two-columns { width: 100%; margin-top: 20px; border-collapse: collapse; page-break-inside: avoid; }
.two-columns td { width: 50%; padding: 16px; vertical-align: top; border: 1px solid #dce3df; }
.two-columns p { margin: 5px 0; }
.notes { margin-top: 18px; padding: 14px; color: white; background: #173e32; page-break-inside: avoid; }
.footer { position: fixed; left: 0; right: 0; bottom: -27px; color: #87938e; font-size: 7px; text-align: center; }
</style>
</head>
<body>
<div class="footer">Shishi Footsteps · {{ $itinerary->code }} · Tailor-made East Africa journeys</div>
<section class="cover">
    @if($cover = $imageData($itinerary->cover_image))<img src="{{ $cover }}">@endif
    <div class="cover-copy"><div class="kicker">{{ $itinerary->countries }} · {{ $itinerary->duration_days }} days</div><h1>{{ $itinerary->title }}</h1><p>{{ $itinerary->summary }}</p></div>
</section>
<table class="facts"><tr>
    <td><small>Duration</small><strong>{{ $itinerary->duration_days }} days / {{ $itinerary->nights }} nights</strong></td>
    <td><small>Route</small><strong>{{ $itinerary->start_location }} to {{ $itinerary->end_location }}</strong></td>
    <td><small>Best time</small><strong>{{ $itinerary->best_time ?: 'Year-round' }}</strong></td>
    <td><small>Price from</small><strong>{{ $itinerary->currency }} {{ number_format($itinerary->price_from) }} per person</strong></td>
</tr></table>
<section class="intro"><div class="kicker">Your journey</div><h2>{{ $itinerary->title }}</h2><p>{!! nl2br(e($itinerary->description ?: $itinerary->summary)) !!}</p></section>
@foreach($itinerary->days as $day)
<section class="day">
    <div class="day-number">Day<b>{{ str_pad($day->day_number, 2, '0', STR_PAD_LEFT) }}</b></div>
    <div class="day-body">
        <span>{{ $day->location }}</span><h3>{{ $day->title }}</h3>
        @if($src = $imageData($day->primary_image))<img class="day-image" src="{{ $src }}">@endif
        @if($day->summary)<p class="lead">{{ $day->summary }}</p>@endif
        <p>{!! nl2br(e($day->description)) !!}</p>
        @if($day->activities)<div class="activities">@foreach($day->activities as $activity)<span>✓ {{ $activity }}</span>@endforeach</div>@endif
        <table class="meta"><tr>
            <td><small>Accommodation</small>{{ $day->accommodation ?: 'To be advised' }}</td>
            <td><small>Meals</small>{{ $day->meal_plan ?: 'As indicated' }}</td>
            <td><small>Journey</small>{{ $day->distance_km ? $day->distance_km.' km · '.number_format($day->driving_hours, 1).' hrs' : 'At leisure' }}</td>
        </tr></table>
    </div><div class="clear"></div>
</section>
@endforeach
<table class="two-columns"><tr>
    <td><div class="kicker">Included</div><h2>What is covered</h2>@foreach($itinerary->inclusions ?? [] as $item)<p>✓ {{ $item }}</p>@endforeach</td>
    <td><div class="kicker">Not included</div><h2>Plan separately</h2>@foreach($itinerary->exclusions ?? [] as $item)<p>× {{ $item }}</p>@endforeach</td>
</tr></table>
@if($itinerary->important_notes)<div class="notes"><strong>Important journey notes</strong><br>{{ $itinerary->important_notes }}</div>@endif
</body>
</html>
