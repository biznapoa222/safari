@if(session('success'))
    <div class="ops-alert ops-alert--success"><i data-lucide="circle-check"></i>{{ session('success') }}</div>
@endif
@if(session('warning'))
    <div class="ops-alert ops-alert--error"><i data-lucide="triangle-alert"></i><div><strong>Attention</strong><span>{{ session('warning') }}</span></div></div>
@endif
@if($errors->any())
    <div class="ops-alert ops-alert--error"><i data-lucide="triangle-alert"></i><div><strong>Please correct the following:</strong><span>{{ $errors->first() }}</span></div></div>
@endif
