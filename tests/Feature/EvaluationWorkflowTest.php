<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EvaluationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->signInAsAdministrator();
    }

    public function test_reservations_can_upload_supplier_invoices_for_evaluation(): void
    {
        Storage::fake('local');
        $quotation = DB::table('quotations')->whereIn('status', ['confirmed', 'in_progress', 'completed'])->first();

        $this->post('/admin/evaluations/reservation-invoices', [
            'quotation_id' => $quotation->id,
            'company_name' => 'Mara Test Camp',
            'comments' => 'Uploaded by reservations for evaluator review.',
            'document' => UploadedFile::fake()->create('mara-invoice.pdf', 120, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $invoice = DB::table('supplier_invoices')->where('company_name', 'Mara Test Camp')->first();
        $this->assertNotNull($invoice);
        $this->assertSame('uploaded', $invoice->status);
        Storage::disk('local')->assertExists($invoice->file_path);

        $this->get("/admin/evaluations/invoices/{$invoice->id}/download")
            ->assertOk()
            ->assertDownload('mara-invoice.pdf');
    }

    public function test_invoice_entries_can_be_matched_approved_and_sent_to_finance(): void
    {
        $quotation = DB::table('quotations')->whereIn('status', ['confirmed', 'in_progress', 'completed'])->first();

        $this->get("/admin/evaluations/{$quotation->id}")
            ->assertOk()
            ->assertSee('Evaluation entries')
            ->assertSee('Finance handoff');

        $this->post("/admin/evaluations/{$quotation->id}/invoices", [
            'invoice_date' => '2026-06-25',
            'invoice_number' => 'INV-EVAL-001',
            'company_name' => 'Shishi Supplier Test',
            'amount' => 5000,
            'currency' => 'USD',
            'invoice_type' => 'normal',
            'vat_rate' => 16,
            'vat_reclaimable' => 1,
            'payment_deadline' => '2026-07-15',
        ])->assertSessionHasNoErrors();

        $invoice = DB::table('supplier_invoices')->where('invoice_number', 'INV-EVAL-001')->first();
        $entries = DB::table('evaluation_entries')->where('quotation_id', $quotation->id)->get();
        $this->assertNotEmpty($entries);

        $entry = $entries->first();
        $this->put("/admin/evaluations/entries/{$entry->id}", [
            'supplier_invoice_id' => $invoice->id,
            'invoice_rate' => $entry->system_rate,
            'meal_plan' => 'Full Board',
            'room_configuration' => 'Double',
            'room_type' => 'Safari Tent',
            'rate_matches' => 1,
            'dates_match' => 1,
            'meal_plan_matches' => 1,
            'room_configuration_matches' => 1,
            'room_type_matches' => 1,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('evaluation_entries', ['id' => $entry->id, 'status' => 'matched']);

        DB::table('evaluation_entries')->where('quotation_id', $quotation->id)->update([
            'supplier_invoice_id' => $invoice->id,
            'status' => 'matched',
            'rate_matches' => true,
            'dates_match' => true,
            'meal_plan_matches' => true,
            'room_configuration_matches' => true,
            'room_type_matches' => true,
        ]);

        $this->post("/admin/evaluations/{$quotation->id}/approve")
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('proposal_evaluations', ['quotation_id' => $quotation->id, 'status' => 'approved']);
        $this->assertDatabaseHas('supplier_invoices', ['id' => $invoice->id, 'status' => 'approved']);

        $this->put("/admin/evaluations/invoices/{$invoice->id}/status", [
            'action' => 'send_to_finance',
            'payment_deadline' => '2026-07-15',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('supplier_invoices', [
            'id' => $invoice->id,
            'status' => 'payment_ready',
            'payment_deadline' => '2026-07-15',
        ]);
    }

    public function test_public_inquiry_and_newsletter_are_saved_in_the_new_frontend_flow(): void
    {
        $this->post('/enquire', [
            'name' => 'Amina Safari Guest',
            'email' => 'amina.safari@example.com',
            'phone' => '+254700000000',
            'country' => 'Kenya',
            'destination' => 'Tanzania',
            'travel_date' => 'October 2026',
            'adults' => 2,
            'children' => 1,
            'budget' => '$8,000 - $12,000',
            'safari_type' => 'Family Safari',
            'message' => 'Private guides and a beach extension.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('leads', [
            'email' => 'amina.safari@example.com',
            'source' => 'website',
            'status' => 'new',
            'travelers' => 3,
        ]);
        $lead = DB::table('leads')->where('email', 'amina.safari@example.com')->first();
        $this->assertNotNull($lead->assigned_consultant_id);
        $this->assertDatabaseHas('users', ['id' => $lead->assigned_consultant_id, 'role' => 'sales']);

        $this->post('/newsletter', ['newsletter_email' => 'amina.safari@example.com'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'amina.safari@example.com',
            'status' => 'subscribed',
        ]);
    }

    public function test_uploaded_invoice_must_be_completed_before_matching(): void
    {
        Storage::fake('local');
        $quotation = DB::table('quotations')->whereIn('status', ['confirmed', 'in_progress', 'completed'])->first();
        $this->get("/admin/evaluations/{$quotation->id}")->assertOk();
        $entry = DB::table('evaluation_entries')->where('quotation_id', $quotation->id)->first();

        $this->post('/admin/evaluations/reservation-invoices', [
            'quotation_id' => $quotation->id,
            'company_name' => 'Incomplete Supplier',
            'document' => UploadedFile::fake()->create('incomplete.pdf', 20, 'application/pdf'),
        ])->assertSessionHasNoErrors();
        $invoice = DB::table('supplier_invoices')->where('company_name', 'Incomplete Supplier')->first();

        $this->put("/admin/evaluations/entries/{$entry->id}", [
            'supplier_invoice_id' => $invoice->id,
            'rate_matches' => 1,
            'dates_match' => 1,
        ])->assertSessionHasErrors('supplier_invoice_id');

        $this->assertDatabaseHas('evaluation_entries', [
            'id' => $entry->id,
            'status' => 'missing_invoice',
        ]);
    }

    public function test_invoice_cannot_skip_approval_before_finance(): void
    {
        $quotation = DB::table('quotations')->whereIn('status', ['confirmed', 'in_progress', 'completed'])->first();
        $invoice = DB::table('supplier_invoices')->insertGetId([
            'quotation_id' => $quotation->id,
            'uploaded_by' => auth()->id(),
            'invoice_date' => '2026-06-25',
            'invoice_number' => 'INV-NOT-APPROVED',
            'company_name' => 'Workflow Supplier',
            'amount' => 1000,
            'currency' => 'USD',
            'invoice_type' => 'normal',
            'status' => 'recorded',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put("/admin/evaluations/invoices/{$invoice}/status", [
            'action' => 'send_to_finance',
            'payment_deadline' => '2026-07-15',
        ])->assertSessionHasErrors('invoice');

        $this->assertDatabaseHas('supplier_invoices', ['id' => $invoice, 'status' => 'recorded']);
    }
}
