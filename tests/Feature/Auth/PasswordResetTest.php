<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('unknown email cannot start password reset', function () {
    $response = $this->postJson('/forgot-password', [
        'email' => 'brak@example.com',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

test('existing email starts password reset session', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertOk();
    expect(session(PasswordResetController::SESSION_EMAIL))->toBe($user->email);
});

test('invalid code is rejected', function () {
    $user = User::factory()->create();

    $this->postJson('/forgot-password', ['email' => $user->email]);

    $response = $this->postJson('/forgot-password/verify-code', [
        'email' => $user->email,
        'code' => '0000',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['code']);
});

test('password can be reset with valid code', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->postJson('/forgot-password', ['email' => $user->email]);

    $this->postJson('/forgot-password/verify-code', [
        'email' => $user->email,
        'code' => PasswordResetController::RESET_CODE,
    ])->assertOk();

    $this->postJson('/reset-password', [
        'email' => $user->email,
        'password' => 'new-secure-password',
        'password_confirmation' => 'new-secure-password',
    ])->assertNoContent();

    $user->refresh();

    expect(Hash::check('new-secure-password', $user->password))->toBeTrue();
});
