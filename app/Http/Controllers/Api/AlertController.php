<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $alerts = Alert::where('userId', $userId)
            ->where('isDeleted', false)
            ->orderByDesc('createdAt')
            ->limit(20)
            ->get(['id', 'description', 'severity', 'type', 'state', 'createdAt']);

        $unreadCount = Alert::where('userId', $userId)
            ->where('isDeleted', false)
            ->where('state', false)
            ->count();

        return response()->json([
            'data' => $alerts,
            'meta' => [
                'unreadCount' => $unreadCount,
            ],
        ]);
    }

    public function markRead(Request $request, int $alertId)
    {
        $userId = $request->user()->id;

        $alert = Alert::where('id', $alertId)
            ->where('userId', $userId)
            ->where('isDeleted', false)
            ->first();

        if (!$alert) {
            return response()->json([
                'message' => 'Powiadomienie nie istnieje.',
            ], 404);
        }

        $alert->update(['state' => true]);

        return response()->json([
            'message' => 'Powiadomienie oznaczone jako przeczytane.',
        ]);
    }
}
