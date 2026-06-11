<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'imie' => ['required', 'string', 'max:255'],
            'nazwisko' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'klub' => ['nullable', 'string', 'max:255'],
            'opis' => ['nullable', 'string', 'max:2000'],
            'avatar' => ['nullable', 'image', 'max:5120'],
            'remove_avatar' => ['nullable', 'boolean'],
            'aktualne_haslo' => ['nullable', 'required_with:nowe_haslo', 'current_password'],
            'nowe_haslo' => ['nullable', 'required_with:aktualne_haslo', 'confirmed', Password::min(8)],
        ]);

        $passwordChanged = ! empty($validated['nowe_haslo']);

        $user->name = $validated['imie'];
        $user->surname = $validated['nazwisko'];
        $user->email = $validated['email'];
        $user->klub = $validated['klub'] ?? null;
        $user->profilDescription = $validated['opis'] ?? null;

        if ($passwordChanged) {
            $user->password = $validated['nowe_haslo'];
        }

        $user->save();

        if ($request->boolean('remove_avatar')) {
            $user->deleteAvatar();
        } elseif ($request->hasFile('avatar')) {
            $user->saveAvatar($request->file('avatar'));
        }

        if ($passwordChanged) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Hasło zostało zmienione. Zaloguj się ponownie.');
        }

        return redirect()
            ->route('profil')
            ->with('success', 'Profil został zaktualizowany.');
    }
}
