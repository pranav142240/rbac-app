<?php

use App\Models\User;
use App\Models\UserAuthMethod;
use App\Models\Otp;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Services\AuthService;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    
    $this->authService = app(AuthService::class);
    $this->permissionService = app(PermissionService::class);
});

describe('User Registration', function () {
    test('can register with email and password', function () {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'auth_method' => 'email_password'
        ];

        $response = $this->post(route('multi-auth.register'), $userData);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'primary_auth_method' => 'email_password'
        ]);
        $this->assertDatabaseHas('user_auth_methods', [
            'auth_type' => 'email_password',
            'identifier' => 'john@example.com'
        ]);
    });

    test('can register with phone and password', function () {
        $userData = [
            'name' => 'Jane Doe',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'auth_method' => 'phone_password'
        ];

        $response = $this->post(route('multi-auth.register'), $userData);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'phone' => '+1234567890',
            'primary_auth_method' => 'phone_password'        ]);
    });
});

describe('User Authentication', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'primary_auth_method' => 'email_password'
        ]);

        UserAuthMethod::create([
            'user_id' => $this->user->id,
            'auth_type' => 'email_password',
            'identifier' => 'test@example.com',
            'is_verified' => true
        ]);
    });

    test('can login with email and password', function () {
        $response = $this->post(route('multi-auth.login'), [
            'auth_method' => 'email_password',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    });

    test('cannot login with invalid credentials', function () {
        $response = $this->post(route('multi-auth.login'), [
            'auth_method' => 'email_password',
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    });

    test('can request otp verification', function () {
        UserAuthMethod::create([
            'user_id' => $this->user->id,
            'auth_type' => 'email_otp',
            'identifier' => 'test@example.com',
            'is_verified' => true
        ]);

        $response = $this->post(route('multi-auth.login'), [
            'auth_method' => 'email_otp',
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect(route('multi-auth.verify-otp'));
        $this->assertDatabaseHas('otps', [
            'user_id' => $this->user->id,
            'type' => 'login'
        ]);
    });
});

describe('OTP Functionality', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('can generate otp', function () {
        $otp = $this->authService->generateOtp($this->user, 'login');

        expect($otp)->toBeInstanceOf(Otp::class);
        expect($otp->code)->toHaveLength(6);
        expect($otp->user_id)->toBe($this->user->id);
        expect($otp->type)->toBe('login');
    });

    test('can verify valid otp', function () {
        $otp = $this->authService->generateOtp($this->user, 'login');

        $result = $this->authService->verifyOtp($this->user, $otp->code, 'login');

        expect($result)->toBeTrue();
    });

    test('cannot verify invalid otp', function () {
        $this->authService->generateOtp($this->user, 'login');

        $result = $this->authService->verifyOtp($this->user, '000000', 'login');

        expect($result)->toBeFalse();
    });

    test('otp expires after configured time', function () {
        $otp = Otp::create([
            'user_id' => $this->user->id,
            'code' => '123456',
            'type' => 'login',
            'expires_at' => now()->subMinutes(1) // Expired 1 minute ago
        ]);

        $result = $this->authService->verifyOtp($this->user, '123456', 'login');

        expect($result)->toBeFalse();
    });
});

describe('Multiple Auth Methods', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'primary_auth_method' => 'email_password'
        ]);
    });

    test('can add additional auth method', function () {
        $this->authService->addAuthMethod($this->user, 'phone_password', '+1234567890');

        $this->assertDatabaseHas('user_auth_methods', [
            'user_id' => $this->user->id,
            'auth_type' => 'phone_password',
            'identifier' => '+1234567890'
        ]);
    });

    test('cannot add duplicate auth method', function () {
        UserAuthMethod::create([
            'user_id' => $this->user->id,
            'auth_type' => 'email_password',
            'identifier' => 'test@example.com',
            'is_verified' => true
        ]);

        expect(function () {
            $this->authService->addAuthMethod($this->user, 'email_password', 'test@example.com');
        })->toThrow(\Exception::class);
    });

    test('user can have multiple verified auth methods', function () {
        UserAuthMethod::create([
            'user_id' => $this->user->id,
            'auth_type' => 'email_password',
            'identifier' => 'test@example.com',
            'is_verified' => true
        ]);

        UserAuthMethod::create([
            'user_id' => $this->user->id,
            'auth_type' => 'phone_otp',
            'identifier' => '+1234567890',
            'is_verified' => true
        ]);        expect($this->user->authMethods()->verified()->count())->toBe(2);
    });
});

describe('Security Features', function () {
    test('passwords are hashed', function () {
        $user = $this->authService->register([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plaintext',
            'auth_method' => 'email_password'
        ]);

        expect($user->password)->not->toBe('plaintext');
        expect(Hash::check('plaintext', $user->password))->toBeTrue();
    });

    test('otp codes are numeric and 6 digits', function () {
        $user = User::factory()->create();
        $otp = $this->authService->generateOtp($user, 'login');

        expect($otp->code)->toMatch('/^\d{6}$/');
    });

    test('sensitive data is not exposed in json', function () {
        $user = User::factory()->create(['password' => Hash::make('secret')]);

        $json = $user->toJson();

        expect($json)->not->toContain('secret');
        expect($json)->not->toContain('password');
    });
});
