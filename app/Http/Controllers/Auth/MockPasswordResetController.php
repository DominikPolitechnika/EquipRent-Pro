<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class MockPasswordResetController extends Controller
{
    private const DEMO_CODE = '1234';

    /**
     * Zaślepka wysyłki kodu weryfikacyjnego - nie wysyła prawdziwego e-maila,
     * kod jest zawsze stały (self::DEMO_CODE).
     */
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return response()->json([
            'message' => 'Jeśli podany adres e-mail istnieje w naszej bazie, wysłaliśmy na niego kod weryfikacyjny.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (trim((string) $request->input('code')) !== self::DEMO_CODE) {
            throw ValidationException::withMessages([
                'code' => ['Nieprawidłowy kod weryfikacyjny.'],
            ]);
        }

        $user = User::where('email', $request->input('email'))
            ->where('isDeleted', false)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Nie znaleziono konta o podanym adresie e-mail.'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')->value()),
        ])->save();

        return response()->json([
            'message' => 'Hasło zostało zmienione. Możesz się teraz zalogować.',
        ]);
    }
}
