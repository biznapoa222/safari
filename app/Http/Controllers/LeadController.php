<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.leads.index', [
            'leads' => DB::table('website_enquiries')
                ->when($request->filled('status'), fn ($query) => $query->where('lifecycle_status', $request->status))
                ->when($request->filled('assigned_to'), fn ($query) => $query->where('assigned_to', $request->assigned_to))
                ->latest()->paginate(25)->withQueryString(),
            'users' => DB::table('users')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, int $lead): RedirectResponse
    {
        $data = $request->validate([
            'assigned_to' => ['nullable', 'string', 'max:180'],
            'lifecycle_status' => ['required', 'in:new,assigned,contacted,qualified,quotation,won,lost,on_trip,completed'],
            'next_follow_up_at' => ['nullable', 'date'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:3000'],
        ]);
        DB::table('website_enquiries')->where('id', $lead)->update([
            ...$data, 'status' => $data['lifecycle_status'], 'updated_at' => now(),
        ]);

        return back()->with('success', 'Request owner and follow-up stage updated.');
    }

    public function convert(int $lead): RedirectResponse
    {
        $enquiry = DB::table('website_enquiries')->find($lead);
        abort_unless($enquiry, 404);

        $quotationId = DB::transaction(function () use ($enquiry) {
            $clientId = DB::table('clients')->where('email', $enquiry->email)->value('id');
            if (! $clientId) {
                $clientId = DB::table('clients')->insertGetId([
                    'name' => $enquiry->name, 'email' => $enquiry->email, 'phone' => null,
                    'country' => $enquiry->country, 'preferred_language' => $enquiry->language_code,
                    'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            $startDate = $enquiry->travel_date ?: today()->addMonths(3)->toDateString();
            $id = DB::table('quotations')->insertGetId([
                'client_id' => $clientId,
                'reference' => 'QT-'.now()->format('Y').'-'.str_pad((string) (DB::table('quotations')->count() + 1), 4, '0', STR_PAD_LEFT),
                'title' => ($enquiry->destination ?: 'Tailor-made East Africa').' Safari for '.$enquiry->name,
                'start_date' => $startDate, 'duration_days' => 7, 'guest_count' => $enquiry->travelers,
                'start_location' => 'Nairobi', 'currency' => 'USD', 'office_markup_percent' => 20,
                'misc_markup_percent' => 5, 'exchange_rate' => 1, 'status' => 'draft',
                'frozen' => false, 'created_at' => now(), 'updated_at' => now(),
            ]);
            for ($day = 1; $day <= 7; $day++) {
                DB::table('quotation_days')->insert([
                    'quotation_id' => $id, 'day_number' => $day,
                    'travel_date' => \Carbon\Carbon::parse($startDate)->addDays($day - 1)->toDateString(),
                    'from_location' => $day === 1 ? 'Nairobi' : null, 'to_location' => null,
                    'description' => null, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('website_enquiries')->where('id', $enquiry->id)->update([
                'converted_quotation_id' => $id, 'lifecycle_status' => 'quotation',
                'status' => 'quotation', 'updated_at' => now(),
            ]);

            return $id;
        });

        return redirect()->route('admin.quotations.show', $quotationId)->with('success', 'Website request converted into a working quotation.');
    }
}
