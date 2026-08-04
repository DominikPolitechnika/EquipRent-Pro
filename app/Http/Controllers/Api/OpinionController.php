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
            ->limit(10)
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
            ->where('is_deleted', false)
            ->exists();

        if (!$productExists) {
            return response()->json([
                'message' => 'Produkt nie istnieje.',
            ], 404);
        }

        $hasCompletedReservation = $this->hasCompletedReservation($userId, $productId);

        if (!$hasCompletedReservation) {
            return response()->json([
                'message' => 'Opinię można dodać dopiero po zakończonym wypożyczeniu produktu.',
            ], 403);
        }

        $alreadyReviewed = $this->alreadyReviewed($userId, $productId);

        if ($alreadyReviewed) {
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

    public function summary(int $productId)
    {
        $summary = DB::table('Opinions')
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->selectRaw('AVG("scaleValue") as average_rating, COUNT(*) as opinions_count')
            ->first();

        return response()->json([
            'data' => [
                'productId' => $productId,
                'averageRating' => $summary->average_rating !== null
                    ? round((float) $summary->average_rating, 2)
                    : null,
                'opinionsCount' => (int) $summary->opinions_count,
            ],
        ]);
    }

    public function canReview(Request $request, int $productId)
    {
        $userId = $request->user()->id;

        $productExists = DB::table('products')
            ->where('id', $productId)
            ->where('is_deleted', false)
            ->exists();

        if (!$productExists) {
            return response()->json([
                'message' => 'Produkt nie istnieje.',
            ], 404);
        }

        $hasCompletedReservation = $this->hasCompletedReservation($userId, $productId);
        $alreadyReviewed = $this->alreadyReviewed($userId, $productId);

        $canReview = $hasCompletedReservation && !$alreadyReviewed;

        if (!$hasCompletedReservation) {
            $message = 'Opinię można dodać dopiero po zakończonym wypożyczeniu.';
        } elseif ($alreadyReviewed) {
            $message = 'Użytkownik dodał już opinię dla tego produktu.';
        } else {
            $message = 'Użytkownik może wystawić opinię.';
        }

        return response()->json([
            'data' => [
                'productId' => $productId,
                'canReview' => $canReview,
                'hasCompletedReservation' => $hasCompletedReservation,
                'alreadyReviewed' => $alreadyReviewed,
                'message' => $message,
            ],
        ]);
    }

    public function update(Request $request, int $opinionId)
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

        $opinion = DB::table('Opinions')
            ->where('id', $opinionId)
            ->where('isDeleted', false)
            ->first();

        if (!$opinion) {
            return response()->json([
                'message' => 'Opinia nie istnieje.',
            ], 404);
        }

        if ((int) $opinion->userId !== (int) $userId) {
            return response()->json([
                'message' => 'Nie możesz edytować cudzej opinii.',
            ], 403);
        }

        DB::table('Opinions')
            ->where('id', $opinionId)
            ->update([
                'description' => $request->input('description'),
                'scaleValue' => $request->input('scaleValue'),
                'updatedAt' => now(),
            ]);

        return response()->json([
            'message' => 'Opinia została zaktualizowana.',
        ]);
    }

    public function destroy(Request $request, int $opinionId)
    {
        $userId = $request->user()->id;

        $opinion = DB::table('Opinions')
            ->where('id', $opinionId)
            ->where('isDeleted', false)
            ->first();

        if (!$opinion) {
            return response()->json([
                'message' => 'Opinia nie istnieje.',
            ], 404);
        }

        if ((int) $opinion->userId !== (int) $userId) {
            return response()->json([
                'message' => 'Nie możesz usunąć cudzej opinii.',
            ], 403);
        }

        DB::table('Opinions')
            ->where('id', $opinionId)
            ->update([
                'isDeleted' => true,
                'updatedAt' => now(),
            ]);

        return response()->json([
            'message' => 'Opinia została usunięta.',
        ]);
    }

    private function hasCompletedReservation(int $userId, int $productId): bool
    {
        return DB::table('reservation')
            ->where('userId', $userId)
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->where(function ($query) {
                $query->whereIn('statusOfReservation', $this->completedStatuses)
                    ->orWhere('endDate', '<', now());
            })
            ->exists();
    }

    private function alreadyReviewed(int $userId, int $productId): bool
    {
        return DB::table('Opinions')
            ->where('userId', $userId)
            ->where('productId', $productId)
            ->where('isDeleted', false)
            ->exists();
    }
}