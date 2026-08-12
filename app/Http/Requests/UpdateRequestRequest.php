<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => ['sometimes', 'string', 'max:255'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'adults' => ['sometimes', 'integer', 'min:1', 'max:999'],
            'children' => ['nullable', 'integer', 'min:0', 'max:999'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:999'],
            'arrival_date' => ['nullable', 'date'],
            'departure_date' => ['nullable', 'date', 'after_or_equal:arrival_date'],
            'nights' => ['nullable', 'integer', 'min:0', 'max:365'],
            'destination' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'accommodation_tier' => ['nullable', 'string', 'in:luxury,midrange,budget,camping'],
            'travel_type' => ['nullable', 'string', 'in:honeymoon,family,group,corporate,solo'],
            'source' => ['nullable', 'string', 'in:manual,website,whatsapp,email,walk_in,api'],
            'language' => ['nullable', 'string', 'size:2'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'company' => ['nullable', 'string', 'max:255'],
            'internal_notes' => ['nullable', 'string'],
            'special_requests' => ['nullable', 'string'],
            'flight_required' => ['nullable', 'boolean'],
            'pickup_required' => ['nullable', 'boolean'],
            'guide_required' => ['nullable', 'boolean'],
            'visa_required' => ['nullable', 'boolean'],
            'insurance_required' => ['nullable', 'boolean'],
            'transport' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'size:3'],
            'itinerary_template_id' => ['nullable', 'integer', 'exists:itinerary_templates,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'adults.min' => 'There must be at least 1 adult.',
            'adults.max' => 'Maximum 999 adults allowed.',
            'departure_date.after_or_equal' => 'Departure date must be on or after arrival date.',
            'accommodation_tier.in' => 'Accommodation tier must be one of: luxury, midrange, budget, camping.',
            'travel_type.in' => 'Travel type must be one of: honeymoon, family, group, corporate, solo.',
            'source.in' => 'Source must be one of: manual, website, whatsapp, email, walk_in, api.',
            'priority.in' => 'Priority must be one of: low, medium, high, urgent.',
            'language.size' => 'Language code must be exactly 2 characters.',
            'currency.size' => 'Currency code must be exactly 3 characters.',
            'assigned_to.exists' => 'The selected assignee does not exist.',
        ];
    }
}
