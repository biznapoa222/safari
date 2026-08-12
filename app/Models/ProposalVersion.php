<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProposalVersion extends Model
{
    protected $fillable = [
        'proposalable_type', 'proposalable_id', 'version_number', 'snapshot_data',
        'rendered_html', 'pdf_path', 'checksum', 'status', 'sent_at', 'viewed_at', 'accepted_at', 'created_by',
    ];
    protected function casts(): array {
        return ['snapshot_data' => 'array', 'sent_at' => 'datetime', 'viewed_at' => 'datetime', 'accepted_at' => 'datetime'];
    }
    public function proposalable() { return $this->morphTo(); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function acceptance(): HasMany { return $this->hasMany(ProposalAcceptance::class); }
    public function changeRequests(): HasMany { return $this->hasMany(ProposalChangeRequest::class); }
}
