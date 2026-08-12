@php
    $fallbackImages = [
        asset('images/itineraries/kenya-family-cover.webp'),
        asset('images/itineraries/tanzania-classic-cover.webp'),
        asset('images/itineraries/botswana-luxury-cover.webp'),
    ];
@endphp
<div class="safari-grid">
    @forelse($safaris as $safari)
        @php
            $image = is_array($safari->images ?? null) && count($safari->images)
                ? \App\Support\MediaPath::publicUrl($safari->images[0])
                : $fallbackImages[$loop->index % count($fallbackImages)];
        @endphp
        <x-public.safari-package-card
            :title="$safari->title"
            :summary="$safari->summary ?? 'A published Shishi Footsteps itinerary ready to be tailored by our safari team.'"
            :image="$image"
            :duration="$safari->duration_days ? $safari->duration_days.' days' : null"
            :country="$safari->country"
            :price="$safari->price_from ? '$'.number_format((float) $safari->price_from) : null"
            :slug="$safari->slug"
        />
    @empty
        <x-public.safari-package-card title="Tailor-Made Private Safari" summary="No matching safaris yet. Tell us your dates, interests and comfort level, and we will build the right safari from scratch." :image="$fallbackImages[0]" duration="Flexible" country="East and Southern Africa" />
    @endforelse
</div>
<div class="pagination-wrap">{{ $safaris->links() }}</div>
