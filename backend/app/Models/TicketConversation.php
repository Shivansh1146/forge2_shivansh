<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TicketConversation extends Model {
    protected $fillable = ['ticket_id','user_id','body','type'];
    public function user() { return $this->belongsTo(User::class); }
    public function ticket() { return $this->belongsTo(Ticket::class); }
}
