<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProposalChangeRequest extends Model
{
    protected $fillable = ['proposal_version_id', 'customer_name', 'customer_email', 'message', 'requested_changes', 'preferred_contact', 'status'];
    public function version(): BelongsTo { return $this->belongsTo(ProposalVersion::class, 'proposal_version_id'); }
}
