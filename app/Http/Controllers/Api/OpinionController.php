<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OpinionController extends Controller
{
    private array $completedStatuses = [
        'completed',
        'finished',
        'zakończona',
        'returned',
        'zwrócona',
    ];

    public function index(int $productId)
    {
        $opinions = DB::table('Opinions')
            ->join('users', 'Opinions.userId', '=', 'users.id')
            ->where('Opinions.productId', $productId)
            ->where('Opinions.isDeleted', false)
            ->select(
                'Opinions.id',
                'Opinions.productId',
                'Opinions.userId',
                'users.name as userName',
                'Opinions.description',
                'Opinions.scaleValue',
                'Opinions.createdAt',
                'Opinions.updatedAt'
            )
            ->orderByDesc('Opinions.createdAt')
            ->get();

        return response()->json([
            'data' => $opinions,
        ]);
    }

    public function store(Request $request, int $productId)
    {
        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'min:3', 'max:1000'],
            'scaleValue' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Niepoprawne dane opinii.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->user()->id;

        $productExists = DB::table('products')
            ->where('id', $productId)
            ->where('isDeleted', false)
            ->exists();

        if (!$productExists) {
            return response()->json([
                'message' => 'Produkt nie istnieje.',
            ], 404);
        }

        $hasCompletedReservation = DB::table('reservation')
            ->where('userId', $userId)
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->where(function ($query) {
                $query->whereIn('statusOfReservation', $this->completedStatuses)
                    ->orWhere('endDate', '<', now());
            })
            ->exists();

        if (!$hasCompletedReservation) {
            return response()->json([
                'message' => 'Opinię można dodać dopiero po zakończonym wypożyczeniu produktu.',
            ], 403);
        }

        $opinionAlreadyExists = DB::table('Opinions')
            ->where('userId', $userId)
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->exists();

        if ($opinionAlreadyExists) {
            return response()->json([
                'message' => 'Dodałeś już opinię dla tego produktu.',
            ], 409);
        }

        $opinionId = DB::table('Opinions')->insertGetId([
            'userId' => $userId,
            'productId' => $productId,
            'description' => $request->input('description'),
            'scaleValue' => $request->input('scaleValue'),
            'createdAt' => now(),
            'updatedAt' => now(),
            'isDeleted' => false,
        ]);

        return response()->json([
            'message' => 'Opinia została dodana.',
            'opinionId' => $opinionId,
        ], 201);
    }
}