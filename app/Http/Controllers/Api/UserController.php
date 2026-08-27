<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $adminRoleIds = Role::where('roleName', 'Administrator')->pluck('id');

        $users = User::with('roleRecord')
            ->where('isDeleted', false)
            ->whereNotIn('role', $adminRoleIds)
            ->orderByDesc('created_at')
            ->get();

        $activityCounts = DB::table('reservation')
            ->select('userId', DB::raw('COUNT(*) as cnt'))
            ->whereIn('userId', $users->pluck('id'))
            ->where('isDeleted', false)
            ->whereNotIn('statusOfReservation', $this->cancelledStatuses)
            ->groupBy('userId')
            ->pluck('cnt', 'userId');

        $data = $users->map(function (User $user) use ($activityCounts) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'fullName' => trim($user->name.' '.$user->surname),
                'email' => $user->email,
                'telephoneNumber' => $user->telephone_number,
                'role' => $user->role,
                'roleName' => $user->roleRecord->roleName ?? null,
                'isBlocked' => (bool) $user->isBlocked,
                'activityCount' => (int) ($activityCounts[$user->id] ?? 0),
                'avatarUrl' => $user->getAvatarUrl(),
                'createdAt' => $user->created_at,
                'lastLogin' => $user->lastLogin,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Zablokuj / odblokuj użytkownika (przełącznik).
     */
    public function toggleBlock(Request $request, int $userID)
    {
        $user = User::where('id', $userID)
            ->where('isDeleted', false)
            ->firstOrFail();

        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Nie możesz zablokować własnego konta.',
            ], 409);
        }

        $user->isBlocked = !$user->isBlocked;
        $user->save();

        return response()->json([
            'message' => $user->isBlocked ? 'Użytkownik został zablokowany.' : 'Użytkownik został odblokowany.',
            'data' => [
                'id' => $user->id,
                'isBlocked' => (bool) $user->isBlocked,
            ],
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

        $rentedItemsCount = (clone $reservationsQuery)->count();

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
        $user = User::with('roleRecord')
            ->where('id', $userID)
            ->where('isDeleted', false)
            ->firstOrFail();

        $reservations = DB::table('reservation')
            ->join('products', 'reservation.productId', '=', 'products.id')
            ->where('reservation.userId', $userID)
            ->where('reservation.isDeleted', false)
            ->select(
                'reservation.id',
                'reservation.productId',
                'products.title as productTitle',
                'reservation.startDate',
                'reservation.endDate',
                'reservation.totalPrice',
                'reservation.statusOfReservation',
                'reservation.createdAt'
            )
            ->orderByDesc('reservation.createdAt')
            ->get();

        $products = Product::whereIn('id', $reservations->pluck('productId')->unique())
            ->get()
            ->keyBy('id');

        $reservations = $reservations->map(function ($reservation) use ($products) {
            $reservation->productThumbnailUrl = $products->get($reservation->productId)?->getThumbnailUrl();
            return $reservation;
        });

        $totalSpent = $reservations
            ->whereNotIn('statusOfReservation', $this->cancelledStatuses)
            ->sum('totalPrice');

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'fullName' => trim($user->name.' '.$user->surname),
                'email' => $user->email,
                'telephoneNumber' => $user->telephone_number,
                'klub' => $user->klub,
                'role' => $user->role,
                'roleName' => $user->roleRecord->roleName ?? null,
                'isBlocked' => (bool) $user->isBlocked,
                'emailVerifiedAt' => $user->email_verified_at,
                'avatarUrl' => $user->getAvatarUrl(),
                'createdAt' => $user->created_at,
                'lastLogin' => $user->lastLogin,
                'totalSpent' => (float) $totalSpent,
                'reservations' => $reservations->values(),
            ],
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
     * Aktualizacja danych użytkownika przez administratora.
     */
    public function update(Request $request, int $id)
    {
        $user = User::where('id', $id)
            ->where('isDeleted', false)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telephone_number' => ['nullable', 'string', 'max:30'],
            'klub' => ['nullable', 'string', 'max:255'],
        ]);

        $user->forceFill($validated)->save();

        return response()->json([
            'message' => 'Dane użytkownika zostały zaktualizowane.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'fullName' => trim($user->name.' '.$user->surname),
                'email' => $user->email,
                'telephoneNumber' => $user->telephone_number,
                'klub' => $user->klub,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
