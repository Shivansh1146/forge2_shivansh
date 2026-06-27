<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'organization_id',
        'subject',
        'description',
        'status',
        'priority',
        'requester_id',
        'assignee_id',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    protected $appends = ['is_breached'];

    public function getIsBreachedAttribute()
    {
        // Closed/resolved tickets are never breached
        if (in_array($this->status, ['resolved', 'closed'])) {
            return false;
        }

        $policy = $this->slaPolicy();

        // No organization or no policy → not breached
        if (!$policy || !$this->created_at) {
            return false;
        }

        return $this->created_at
            ->copy()
            ->addMinutes($policy->resolution_time_minutes)
            ->isPast();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(TicketConversation::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function slaPolicy()
    {
        // Prevent "Call to member function on null"
        if (!$this->organization) {
            return null;
        }

        return $this->organization
            ->slaPolicies()
            ->where('priority', $this->priority)
            ->first();
    }
}
