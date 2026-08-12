@if(isset($stats))
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:16px">
    <div class="ops-panel" style="padding:16px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="color:var(--text-muted);font-size:7px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Total</span>
            <i data-lucide="file-text" style="width:14px;height:14px;color:var(--primary)"></i>
        </div>
        <p style="margin:0;font-size:18px;font-weight:700;color:var(--text)">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="ops-panel" style="padding:16px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="color:var(--text-muted);font-size:7px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">New</span>
            <i data-lucide="inbox" style="width:14px;height:14px;color:#5b8def"></i>
        </div>
        <p style="margin:0;font-size:18px;font-weight:700;color:var(--text)">{{ $stats['new'] ?? 0 }}</p>
    </div>
    <div class="ops-panel" style="padding:16px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="color:var(--text-muted);font-size:7px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Follow-ups Due</span>
            <i data-lucide="bell" style="width:14px;height:14px;color:#e98255"></i>
        </div>
        <p style="margin:0;font-size:18px;font-weight:700;color:var(--text)">{{ $stats['followups_due'] ?? 0 }}</p>
    </div>
    <div class="ops-panel" style="padding:16px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="color:var(--text-muted);font-size:7px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Conversion Rate</span>
            <i data-lucide="trending-up" style="width:14px;height:14px;color:#22c55e"></i>
        </div>
        <p style="margin:0;font-size:18px;font-weight:700;color:var(--text)">{{ $stats['conversion_rate'] ?? 0 }}%</p>
    </div>
    <div class="ops-panel" style="padding:16px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <span style="color:var(--text-muted);font-size:7px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Avg Value</span>
            <i data-lucide="dollar-sign" style="width:14px;height:14px;color:#22c55e"></i>
        </div>
        <p style="margin:0;font-size:18px;font-weight:700;color:var(--text)">{{ isset($stats['avg_value']) ? number_format($stats['avg_value'], 0) : 0 }}</p>
    </div>
</div>
@endif
