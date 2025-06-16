<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('login screen can be rendered', function () {
    $response = $this->get('/auth/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'primary_auth_method' => 'email_password'
    ]);
    
    // Create the auth method entry
    $authMethod = $user->authMethods()->create([
        'auth_method_type' => 'email_password',
        'identifier' => 'test@example.com',
        'auth_method_verified_at' => now(), // Set verified timestamp
        'is_active' => true
    ]);
    
    // Verify the auth method was created
    expect($user->authMethods()->count())->toBe(1);
    expect($authMethod->auth_method_type)->toBe('email_password');
    expect($authMethod->isVerified())->toBe(true);

    $response = $this->post(route('auth.login.post'), [
        'identifier' => 'test@example.com',
        'password' => 'password',
        'auth_type' => 'email_password'
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/auth/login', [
        'identifier' => $user->email,
        'password' => 'wrong-password',
        'auth_type' => 'email_password'
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/auth/logout');

    $this->assertGuest();
    $response->assertRedirect('/auth/login');
});
