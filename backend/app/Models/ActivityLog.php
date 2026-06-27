<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model {
    protected $fillable = ['ticket_id','user_id','action','meta'];
    protected $casts = ['meta' => 'array'];
    public function user() { return $this->belongsTo(User::class); }
}
