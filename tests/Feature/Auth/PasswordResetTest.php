<?php

use App\Models\User;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    $user = User::factory()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
        'catchphrase' => $user->catchphrase,
    ]);

    $response->assertRedirect(route('password.reset.form'));
    $this->assertSame($user->email, session('password_reset_email'));
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();
    $this->withSession(['password_reset_email' => $user->email]);

    $response = $this->get(route('password.reset.form'));
    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();
    $this->withSession(['password_reset_email' => $user->email]);

    $response = $this->post(route('password.reset.update'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $user->fresh()->password));
});
