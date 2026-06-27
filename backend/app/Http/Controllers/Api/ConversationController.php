<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
class ConversationController extends Controller {
    public function index(Request $request, $ticketId) {
        $ticket = Ticket::where('organization_id', $request->user()->organization_id)->findOrFail($ticketId);
        $query = $ticket->conversations()->with('user');
        if ($request->user()->role === 'customer') {
            $query->where('type', 'public_reply');
        }
        return response()->json($query->latest()->get());
    }
    public function store(Request $request, $ticketId) {
        $ticket = Ticket::where('organization_id', $request->user()->organization_id)->findOrFail($ticketId);
        $data = $request->validate([
            'body' => 'required|string',
            'type' => 'in:public_reply,internal_note'
        ]);
        if ($request->user()->role === 'customer') $data['type'] = 'public_reply';
        $data['ticket_id'] = $ticket->id;
        $data['user_id'] = $request->user()->id;
        $conv = TicketConversation::create($data);
        ActivityLog::create(['ticket_id'=>$ticket->id,'user_id'=>$request->user()->id,'action'=>'replied','meta'=>['type'=>$data['type']]]);
        return response()->json($conv->load('user'), 201);
    }
}
