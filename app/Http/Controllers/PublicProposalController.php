<?php
namespace App\Http\Controllers;
use App\Models\ProposalVersion;
use App\Models\ProposalAcceptance;
use App\Models\ProposalChangeRequest;
use App\Models\ItineraryTemplate;
use App\Models\ProposalTemplateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PublicProposalController extends Controller
{
    public function show(string $token): View
    {
        $version = ProposalVersion::where('checksum', $token)->with('proposalable')->firstOrFail();

        if (!$version->viewed_at) {
            $version->update(['viewed_at' => now(), 'status' => 'viewed']);
        }

        $proposal = $version->proposalable;
        $template = null;
        $settings = null;

        if ($proposal instanceof ItineraryTemplate) {
            $template = $proposal;
            $template->load('days.destination', 'days.hotel', 'days.activities', 'pricing');
        } elseif ($proposal->relationLoaded('template')) {
            $template = $proposal->template;
        }

        if ($template) {
            $settings = ProposalTemplateSetting::where('itinerary_template_id', $template->id)->first();
        }

        return view('itinerary-templates.luxury-dark.proposal', [
            'version' => $version,
            'proposal' => $proposal,
            'template' => $template,
            'settings' => $settings ? $settings->settings : [],
            'agency' => (object) [
                'name' => 'Shishi Footsteps',
                'tagline' => 'Luxury African Safari Experiences',
                'logo' => asset('images/brand/shishi-paw-white.png'),
                'email' => 'info@shishifootsteps.com',
                'phone' => '+254 700 000 000',
                'location' => 'Nairobi, Kenya',
                'years_experience' => 10,
                'safaris_planned' => 500,
                'destinations_covered' => 8,
            ],
        ]);
    }

    public function accept(Request $request, string $token)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        $version = ProposalVersion::where('checksum', $token)->firstOrFail();

        if ($version->status === 'accepted') {
            return response()->json(['message' => 'This proposal has already been accepted.'], 422);
        }

        DB::transaction(function () use ($version, $validated, $request) {
            ProposalAcceptance::create([
                'proposal_version_id' => $version->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'accepted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $version->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your safari proposal has been accepted. We will be in touch shortly.',
        ]);
    }

    public function requestChanges(Request $request, string $token)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'requested_changes' => ['nullable', 'string', 'max:10000'],
            'preferred_contact' => ['nullable', 'string', 'max:100'],
        ]);

        $version = ProposalVersion::where('checksum', $token)->firstOrFail();

        DB::transaction(function () use ($version, $validated) {
            ProposalChangeRequest::create([
                'proposal_version_id' => $version->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'message' => $validated['message'],
                'requested_changes' => $validated['requested_changes'],
                'preferred_contact' => $validated['preferred_contact'],
                'status' => 'pending',
            ]);

            $version->update(['status' => 'changes_requested']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Your change request has been submitted. Your consultant will review and respond.',
        ]);
    }

    public function download(string $token)
    {
        $version = ProposalVersion::where('checksum', $token)->firstOrFail();

        if ($version->pdf_path && file_exists(storage_path('app/' . $version->pdf_path))) {
            return response()->download(storage_path('app/' . $version->pdf_path));
        }

        $proposal = $version->proposalable;
        $template = $proposal instanceof ItineraryTemplate ? $proposal : $proposal->template;

        $html = view('itinerary-templates.luxury-dark.proposal', [
            'version' => $version,
            'proposal' => $proposal,
            'template' => $template,
            'settings' => [],
            'agency' => (object) [
                'name' => 'Shishi Footsteps',
                'tagline' => 'Luxury African Safari Experiences',
                'logo' => asset('images/brand/shishi-paw-white.png'),
                'email' => 'info@shishifootsteps.com',
                'phone' => '+254 700 000 000',
            ],
            'pdf' => true,
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        return $pdf->download($version->checksum . '-safari-proposal.pdf');
    }
}
