<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProposalWorkspaceController extends Controller
{
    public function travelInfoUpdate(Request $request, int $quotation): RedirectResponse
    {
        $data=$request->validate(['customer_message'=>['nullable','string','max:5000'],'arrival_time'=>['nullable','date_format:H:i'],'arrival_location'=>['nullable','string','max:180'],'arrival_flight'=>['nullable','string','max:80'],'departure_time'=>['nullable','date_format:H:i'],'departure_location'=>['nullable','string','max:180'],'departure_flight'=>['nullable','string','max:80'],'dietary_requests'=>['nullable','string','max:2000'],'announcements'=>['nullable','string','max:2000'],'customer_notes'=>['nullable','string','max:2000']]);
        DB::table('proposal_workflows')->where('quotation_id',$quotation)->update([...$data,'updated_at'=>now()]);
        self::capture($quotation,(int)$request->user()->id,'Automatic · travel request information updated');
        return back()->with('success','Travel request information saved.');
    }
    public function snapshot(Request $request, int $quotation): RedirectResponse
    {
        self::capture($quotation,(int)$request->user()->id,$request->input('label') ?: 'Snapshot '.now()->format('d-m-Y H:i'));
        return redirect()->route('admin.quotations.show', [$quotation, 'tab' => 'snapshots'])->with('success', 'Snapshot created. Every proposal and file change can now be compared.');
    }

    public static function capture(int $quotation, ?int $userId, string $label): void
    {
        $quote = DB::table('quotations')->find($quotation); abort_unless($quote, 404);
        $payload = self::snapshotPayload($quotation);
        DB::table('proposal_snapshots')->insert([
            'quotation_id' => $quotation, 'created_by' => $userId, 'status' => $quote->status,
            'price' => $quote->sell_total, 'exchange_rate' => $quote->exchange_rate,
            'label' => $label,
            'snapshot_data' => json_encode($payload), 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function travelerStore(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate(['salutation' => ['nullable','string','max:20'], 'first_name' => ['required','string','max:100'], 'surname' => ['required','string','max:100'], 'date_of_birth' => ['nullable','date']]);
        DB::table('proposal_travelers')->insert([...$data, 'quotation_id' => $quotation, 'created_at' => now(), 'updated_at' => now()]);
        self::capture($quotation,(int)$request->user()->id,'Automatic · person added');
        return back()->with('success', 'Person added to the proposal.');
    }

    public function travelerDestroy(int $quotation, int $traveler): RedirectResponse
    {
        DB::table('proposal_travelers')->where('quotation_id',$quotation)->where('id',$traveler)->delete();
        self::capture($quotation,(int)auth()->id(),'Automatic · person removed');
        return back()->with('success', 'Person removed.');
    }

    public function adjustmentStore(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate(['type' => ['required','in:supplement,surcharge,discount'], 'description' => ['required','string','max:180'], 'calculation_type' => ['required','in:fixed_price,per_person,vehicle'], 'unit_amount' => ['required','numeric','min:0'], 'quantity' => ['required','numeric','min:0'], 'currency' => ['required','string','size:3'], 'notes' => ['nullable','string','max:1000']]);
        DB::table('proposal_adjustments')->insert([...$data, 'quotation_id'=>$quotation, 'created_at'=>now(), 'updated_at'=>now()]);
        self::capture($quotation,(int)$request->user()->id,'Automatic · '.ucfirst($data['type']).' added');
        return back()->with('success', ucfirst($data['type']).' saved.');
    }

    public function adjustmentDestroy(int $quotation, int $adjustment): RedirectResponse
    {
        DB::table('proposal_adjustments')->where('quotation_id',$quotation)->where('id',$adjustment)->delete();
        self::capture($quotation,(int)auth()->id(),'Automatic · price adjustment removed');
        return back()->with('success', 'Price adjustment removed.');
    }

    public function documentStore(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate(['category'=>['required','in:customer_briefing,guide_briefing,ticket,pdf,other'], 'document'=>['required','file','max:15360']]);
        $file = $request->file('document'); $path = $file->store("proposal-documents/{$quotation}");
        DB::table('proposal_documents')->insert(['quotation_id'=>$quotation,'uploaded_by'=>$request->user()->id,'category'=>$data['category'],'file_name'=>$file->getClientOriginalName(),'path'=>$path,'mime_type'=>$file->getMimeType(),'size'=>$file->getSize(),'created_at'=>now(),'updated_at'=>now()]);
        self::capture($quotation,(int)$request->user()->id,'Automatic · file added: '.$file->getClientOriginalName());
        return back()->with('success', 'Client file uploaded and included in future snapshots.');
    }

    public function documentDownload(int $quotation, int $document): BinaryFileResponse
    {
        $record = DB::table('proposal_documents')->where('quotation_id',$quotation)->where('id',$document)->first(); abort_unless($record && Storage::exists($record->path),404);
        return response()->download(Storage::path($record->path), $record->file_name);
    }

    public function documentDestroy(int $quotation, int $document): RedirectResponse
    {
        $record = DB::table('proposal_documents')->where('quotation_id',$quotation)->where('id',$document)->first(); abort_unless($record,404);
        Storage::delete($record->path); DB::table('proposal_documents')->where('id',$document)->delete();
        self::capture($quotation,(int)auth()->id(),'Automatic · file removed: '.$record->file_name);
        return back()->with('success', 'Client file removed. The change will appear in the next snapshot comparison.');
    }

    public static function compareSnapshots(object $older, object $newer): array
    {
        $a = json_decode($older->snapshot_data, true) ?: []; $b = json_decode($newer->snapshot_data, true) ?: [];
        $changes = [];
        foreach (['quotation','travel_information','travelers','days','items','adjustments','documents','reservations'] as $group) {
            if (($a[$group] ?? []) !== ($b[$group] ?? [])) $changes[$group] = ['before'=>$a[$group] ?? [], 'after'=>$b[$group] ?? []];
        }
        return $changes;
    }

    private static function snapshotPayload(int $quotation): array
    {
        $days = DB::table('quotation_days')->where('quotation_id',$quotation)->orderBy('day_number')->get();
        return [
            'quotation'=>(array)DB::table('quotations')->find($quotation),
            'travel_information'=>(array)DB::table('proposal_workflows')->where('quotation_id',$quotation)->first(),
            'travelers'=>DB::table('proposal_travelers')->where('quotation_id',$quotation)->orderBy('id')->get()->map(fn($x)=>(array)$x)->all(),
            'days'=>$days->map(fn($x)=>(array)$x)->all(),
            'items'=>DB::table('quotation_items')->whereIn('quotation_day_id',$days->pluck('id'))->orderBy('id')->get()->map(fn($x)=>(array)$x)->all(),
            'adjustments'=>DB::table('proposal_adjustments')->where('quotation_id',$quotation)->orderBy('id')->get()->map(fn($x)=>(array)$x)->all(),
            'documents'=>DB::table('proposal_documents')->where('quotation_id',$quotation)->orderBy('id')->get(['category','file_name','size','created_at'])->map(fn($x)=>(array)$x)->all(),
            'reservations'=>DB::table('reservations')->where('quotation_id',$quotation)->orderBy('id')->get()->map(fn($x)=>(array)$x)->all(),
        ];
    }
}
