<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private array $cancelledStatuses = [
        'cancelled',
        'canceled',
        'anulowana',
    ];

    private array $completedStatuses = [
        'completed',
        'finished',
        'zakończona',
        'returned',
        'zwrócona',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('isDeleted', false)->get();

        return response()->json([
            'data' => $users,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if ($user->isDeleted) {
            return response()->json([
                'message' => 'Konto nie istnieje.',
            ], 404);
        }

        $reservationsQuery = DB::table('reservation')
            ->where('userId', $user->id)
            ->where('isDeleted', false)
            ->whereNotIn('statusOfReservation', $this->cancelledStatuses);

        $activeRentalsCount = (clone $reservationsQuery)
            ->whereNotIn('statusOfReservation', $this->completedStatuses)
            ->where('endDate', '>=', now())
            ->count();

        $rentedItemsCount = (clone $reservationsQuery)
            ->whereIn('statusOfReservation', $this->completedStatuses)
            ->count();

        $totalSpent = (clone $reservationsQuery)->sum('totalPrice');

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'role' => $user->role,
                'klub' => $user->klub,
                'profilDescription' => $user->profilDescription,
                'lastLogin' => $user->lastLogin,
                'avatarUrl' => $user->getAvatarUrl(),
                'activeRentalsCount' => $activeRentalsCount,
                'rentedItemsCount' => $rentedItemsCount,
                'totalSpent' => (float) $totalSpent,
            ],
        ]);
    }

    public function getUsersDetails(int $userID)
    {
        $user = User::where('id', $userID)
            ->where('isDeleted', false)
            ->firstOrFail();

        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
