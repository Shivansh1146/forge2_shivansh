<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
class TicketController extends Controller {
    public function index(Request $request) {
        $query = Ticket::where('organization_id', $request->user()->organization_id)
            ->with(['requester','assignee']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);
        if ($request->assignee_id) $query->where('assignee_id', $request->assignee_id);
        if ($request->search) $query->where(function($q) use ($request) {
            $q->where('subject', 'like', "%{$request->search}%")
              ->orWhere('description', 'like', "%{$request->search}%");
        });
        if ($request->user()->role === 'customer') {
            $query->where('requester_id', $request->user()->id);
        }
        if ($request->breached === 'true') {
            $tickets = $query->get()->filter->is_breached->values();
            return response()->json([
                'data' => $tickets,
                'last_page' => 1
            ]);
        }
        return response()->json($query->latest()->paginate(20));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
            'priority' => 'in:low,medium,high,urgent',
            'assignee_id' => 'nullable|exists:users,id',
            'tags' => 'nullable|array'
        ]);
        $data['organization_id'] = $request->user()->organization_id;
        $data['requester_id'] = $request->user()->id;
        $data['status'] = 'open';
        $ticket = Ticket::create($data);
        ActivityLog::create(['ticket_id'=>$ticket->id,'user_id'=>$request->user()->id,'action'=>'created','meta'=>null]);
        return response()->json($ticket->load(['requester','assignee']), 201);
    }
    public function show(Request $request, $id) {
        $ticket = Ticket::where('organization_id', $request->user()->organization_id)
            ->with(['requester','assignee','conversations.user','activityLogs.user'])
            ->findOrFail($id);
        if ($request->user()->role === 'customer' && $ticket->requester_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($ticket);
    }
    public function update(Request $request, $id) {
        $ticket = Ticket::where('organization_id', $request->user()->organization_id)->findOrFail($id);
        $old = $ticket->toArray();
        $data = $request->validate([
            'subject' => 'sometimes|string',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:open,pending,resolved,closed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assignee_id' => 'nullable|exists:users,id',
            'tags' => 'nullable|array'
        ]);
        $ticket->update($data);
        if (isset($data['status']) && $old['status'] !== $data['status']) {
            ActivityLog::create(['ticket_id'=>$ticket->id,'user_id'=>$request->user()->id,'action'=>'status_changed','meta'=>['from'=>$old['status'],'to'=>$data['status']]]);
        }
        if (isset($data['assignee_id']) && $old['assignee_id'] !== $data['assignee_id']) {
            ActivityLog::create(['ticket_id'=>$ticket->id,'user_id'=>$request->user()->id,'action'=>'reassigned','meta'=>['to'=>$data['assignee_id']]]);
        }
        return response()->json($ticket->fresh()->load(['requester','assignee']));
    }
    public function destroy(Request $request, $id) {
        $ticket = Ticket::where('organization_id', $request->user()->organization_id)->findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
