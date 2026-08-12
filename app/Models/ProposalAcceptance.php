<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProposalAcceptance extends Model
{
    protected $fillable = ['proposal_version_id', 'customer_name', 'customer_email', 'accepted_at', 'ip_address', 'user_agent', 'signature'];
    protected function casts(): array { return ['accepted_at' => 'datetime']; }
    public function version(): BelongsTo { return $this->belongsTo(ProposalVersion::class, 'proposal_version_id'); }
}
