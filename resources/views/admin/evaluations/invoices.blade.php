@extends('layouts.admin')
@section('title', 'Reservation Invoices')
@section('content')
<div class="ops-page-heading">
    <div><p class="eyebrow">Reservations</p><h1>Supplier invoices</h1><p>Upload every accommodation, activity, transport and vehicle invoice before evaluation.</p></div>
    <div class="heading-actions"><a class="button button-secondary" href="{{ route('admin.evaluations.index') }}"><i data-lucide="clipboard-check"></i>Evaluation queue</a></div>
</div>
@include('admin.partials.flash')

<div class="invoice-upload-layout">
    <section class="ops-panel ops-form-panel invoice-upload-panel">
        <div class="ops-panel-title"><div><h2>Upload supplier invoice</h2><p>PDF, JPG, PNG or WebP, maximum 10 MB.</p></div><i data-lucide="upload-cloud"></i></div>
        <form method="POST" action="{{ route('admin.evaluations.invoices.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="ops-form-grid">
                <label class="span-2">Confirmed proposal
                    <select name="quotation_id" required data-invoice-quotation>
                        <option value="">Select proposal</option>
                        @foreach($quotations as $quotation)<option value="{{ $quotation->id }}">{{ $quotation->reference }} - {{ $quotation->title }}</option>@endforeach
                    </select>
                </label>
                <label class="span-2">Reservation (optional)
                    <select name="reservation_id">
                        <option value="">General proposal invoice</option>
                        @foreach($reservations as $reservation)<option value="{{ $reservation->id }}">{{ $reservation->quotation_reference }} - {{ ucfirst($reservation->reservation_type) }} - {{ $reservation->supplier ?: 'Supplier' }}</option>@endforeach
                    </select>
                </label>
                <label class="span-2">Supplier / company<input name="company_name" value="{{ old('company_name') }}" required></label>
                <label class="span-2">Invoice file<input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.webp" required></label>
                <label class="span-2">Reservation note<textarea name="comments" rows="4" placeholder="Missing details, supplier contact or follow-up note">{{ old('comments') }}</textarea></label>
            </div>
            <div class="ops-form-footer"><button class="button button-primary"><i data-lucide="upload"></i>Upload invoice</button></div>
        </form>
    </section>

    <section class="ops-panel">
        <form class="ops-filters" method="GET">
            <select name="status"><option value="">All statuses</option>@foreach(['uploaded','recorded','evaluated','requires_amendment','approved','payment_ready','paid'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>@endforeach</select>
            <button class="button button-primary">Filter</button>
        </form>
        <div class="table-wrap"><table class="ops-table"><thead><tr><th>Proposal</th><th>Supplier</th><th>Invoice</th><th>Uploaded by</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($invoices as $invoice)
            <tr>
                <td><strong>{{ $invoice->quotation_reference }}</strong><small>{{ $invoice->client_name }}</small></td>
                <td>{{ $invoice->company_name }}</td>
                <td>{{ $invoice->invoice_number ?: 'Details pending' }}<small>{{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d M Y H:i') : '' }}</small></td>
                <td>{{ $invoice->uploader_name ?: 'System' }}</td>
                <td><span class="ops-pill {{ in_array($invoice->status, ['approved','payment_ready','paid']) ? 'ops-pill--green' : ($invoice->status === 'requires_amendment' ? 'ops-pill--red' : 'ops-pill--blue') }}">{{ ucwords(str_replace('_', ' ', $invoice->status)) }}</span></td>
                <td class="ops-actions">@if($invoice->file_path)<a href="{{ route('admin.evaluations.invoices.download', $invoice->id) }}" target="_blank" title="View invoice"><i data-lucide="file-search"></i></a>@endif<a href="{{ route('admin.evaluations.show', $invoice->quotation_id) }}" title="Open evaluation"><i data-lucide="arrow-up-right"></i></a></td>
            </tr>
        @empty<tr><td colspan="6" class="empty-cell">No supplier invoices have been uploaded.</td></tr>@endforelse
        </tbody></table></div>
        <div class="ops-pagination">{{ $invoices->links() }}</div>
    </section>
</div>
@endsection
