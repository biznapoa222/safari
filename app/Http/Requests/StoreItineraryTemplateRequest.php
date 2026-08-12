<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItineraryTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:99'],
            'category' => ['nullable', 'string', 'in:luxury,midrange,budget,camping'],
            'overview' => ['nullable', 'string'],
            'highlights' => ['nullable', 'string'],
            'includes' => ['nullable', 'string'],
            'excludes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'days' => ['nullable', 'array'],
            'days.*.day_number' => ['required', 'integer'],
            'days.*.title' => ['nullable', 'string'],
            'days.*.destination' => ['nullable', 'string'],
            'days.*.accommodation' => ['nullable', 'string'],
            'days.*.meal_plan' => ['nullable', 'string'],
            'days.*.morning_activity' => ['nullable', 'string'],
            'days.*.afternoon_activity' => ['nullable', 'string'],
            'days.*.evening_activity' => ['nullable', 'string'],
            'days.*.description' => ['nullable', 'string'],
            'days.*.notes' => ['nullable', 'string'],
        ];
    }
}
