<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /** Tymczasowy kod resetu (docelowo: wysyłka e-mailem). */
    public const RESET_CODE = '1234';

    public const SESSION_EMAIL = 'password_reset.email';

    public const SESSION_VERIFIED = 'password_reset.code_verified';

    /**
     * Krok 1: sprawdź, czy użytkownik istnieje.
     *
     * @throws ValidationException
     */
    public function requestEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (! User::where('email', $validated['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => ['Nie znaleziono konta z tym adresem e-mail.'],
            ]);
        }

        $request->session()->put(self::SESSION_EMAIL, $validated['email']);
        $request->session()->forget(self::SESSION_VERIFIED);

        return response()->json([
            'message' => 'Konto znalezione. Wprowadź kod resetujący.',
        ]);
    }

    /**
     * Krok 2: weryfikacja kodu.
     *
     * @throws ValidationException
     */
    public function verifyCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
        ]);

        if ($request->session()->get(self::SESSION_EMAIL) !== $validated['email']) {
            throw ValidationException::withMessages([
                'email' => ['Najpierw potwierdź adres e-mail.'],
            ]);
        }

        if ($validated['code'] !== self::RESET_CODE) {
            throw ValidationException::withMessages([
                'code' => ['Nieprawidłowy kod.'],
            ]);
        }

        $request->session()->put(self::SESSION_VERIFIED, true);

        return response()->json([
            'message' => 'Kod poprawny. Ustaw nowe hasło.',
        ]);
    }

    /**
     * Krok 3: ustawienie nowego hasła.
     *
     * @throws ValidationException
     */
    public function resetPassword(Request $request): Response
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (
            $request->session()->get(self::SESSION_EMAIL) !== $validated['email']
            || ! $request->session()->get(self::SESSION_VERIFIED)
        ) {
            throw ValidationException::withMessages([
                'email' => ['Sesja resetowania wygasła. Zacznij od początku.'],
            ]);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();

        $user->forceFill([
            'password' => $validated['password'],
        ])->save();

        $request->session()->forget([self::SESSION_EMAIL, self::SESSION_VERIFIED]);

        return response()->noContent();
    }
}
