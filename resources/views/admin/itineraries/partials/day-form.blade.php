<form class="itinerary-day-form" method="POST" enctype="multipart/form-data" action="{{ $day ? route('admin.itineraries.days.update', [$itinerary, $day]) : route('admin.itineraries.days.store', $itinerary) }}">
    @csrf @if($day) @method('PUT') @endif
    <div class="ops-form-grid">
        <label>Day number<input type="number" name="day_number" value="{{ $day?->day_number }}" min="1" placeholder="Next"></label>
        <label>Day title<input name="title" value="{{ $day?->title }}" placeholder="Arrival in Nairobi" required></label>
        <label>Location<input name="location" value="{{ $day?->location }}" placeholder="Nairobi"></label>
        <label>Overnight location<input name="overnight" value="{{ $day?->overnight }}" placeholder="Nairobi"></label>
        <label>Accommodation<input name="accommodation" value="{{ $day?->accommodation }}" placeholder="Hotel or safari camp"></label>
        <label>Meal plan<input name="meal_plan" value="{{ $day?->meal_plan }}" placeholder="Breakfast, lunch and dinner"></label>
        <label>Distance (km)<input type="number" name="distance_km" value="{{ $day?->distance_km }}" min="0"></label>
        <label>Driving hours<input type="number" step="0.25" name="driving_hours" value="{{ $day?->driving_hours }}" min="0" max="24"></label>
        <label class="span-2">Day summary<textarea name="summary" rows="2">{{ $day?->summary }}</textarea></label>
        <label class="span-2">Detailed day description<textarea name="description" rows="6">{{ $day?->description }}</textarea></label>
        <label>Activities, one per line<textarea name="activities_text" rows="5">{{ implode("\n", $day?->activities ?? []) }}</textarea></label>
        <label>Primary day image<input type="file" name="primary_image_upload" accept="image/jpeg,image/png,image/webp"></label>
        <label class="span-2">Additional day gallery<input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple></label>
        <label>Gallery caption<input name="caption" placeholder="Optional shared caption"></label>
        <label>Photo credit<input name="credit" placeholder="Optional credit"></label>
    </div>
    <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="save"></i>{{ $day ? 'Save day' : 'Add day' }}</button></div>
</form>
