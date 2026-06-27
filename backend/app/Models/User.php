<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class User extends Authenticatable {
    use HasApiTokens, Notifiable;
    protected $fillable = ['name','email','password','organization_id','role'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['password' => 'hashed'];
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function assignedTickets() { return $this->hasMany(Ticket::class, 'assignee_id'); }
    public function requestedTickets() { return $this->hasMany(Ticket::class, 'requester_id'); }
}
