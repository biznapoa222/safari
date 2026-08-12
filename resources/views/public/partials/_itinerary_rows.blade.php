@forelse($safaris as $safari)
    <article class="itinerary-row">
        <span>{{ $safari->duration_days ?? 'Custom' }} days</span>
        <div>
            <h2><a href="{{ route('public.itineraries.show', $safari->slug) }}" class="itinerary-title-link">{{ $safari->title }}</a></h2>
            <p>{{ $safari->summary ?? 'A thoughtfully designed Shishi Footsteps itinerary with day-by-day routing and expert guiding.' }}</p>
        </div>
        <a href="{{ route('public.itineraries.show', $safari->slug) }}">View itinerary<i data-lucide="arrow-up-right"></i></a>
    </article>
@empty
    <article class="itinerary-row">
        <span>Custom</span>
        <div><h2>Tailor-made itinerary planning</h2><p>No itineraries match your filter. Tell us what you have in mind and our team will build a route from your wishlist.</p></div>
        <a href="{{ route('public.booking') }}">Start planning<i data-lucide="arrow-up-right"></i></a>
    </article>
@endforelse
<div class="pagination-wrap">{{ $safaris->links() }}</div>
