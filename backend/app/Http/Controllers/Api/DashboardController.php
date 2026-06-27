<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
    public function index(Request $request) {
        $orgId = $request->user()->organization_id;
        $byStatus = Ticket::where('organization_id', $orgId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->get();
        $byPriority = Ticket::where('organization_id', $orgId)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')->get();
        $recentTickets = Ticket::where('organization_id', $orgId)
            ->with(['requester','assignee'])
            ->latest()->take(5)->get();
        return response()->json(compact('byStatus','byPriority','recentTickets'));
    }
}
