<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupOtpMail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_via_multi_step_wizard(): void
    {
        Mail::fake();

        // 1. Post to step 1 (Email)
        $response = $this->post('/register', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('register.step2'));
        $this->assertEquals('test@example.com', session('signup_email'));

        // Retrieve the generated OTP from Cache
        $otp = Cache::get('signup_otp_test@example.com');
        $this->assertNotNull($otp);

        Mail::assertSent(SignupOtpMail::class, function ($mail) use ($otp) {
            return $mail->otp === $otp;
        });

        // 2. Post to step 2 (OTP verification)
        $response = $this->withSession(['signup_email' => 'test@example.com'])
            ->post('/register/step2', [
                'otp' => $otp,
            ]);

        $response->assertRedirect(route('register.step3'));
        $this->assertTrue(session('signup_email_verified'));

        // 3. Post to step 3 (Complete Profile)
        $response = $this->withSession([
            'signup_email' => 'test@example.com',
            'signup_email_verified' => true,
        ])->post('/register/step3', [
            'name' => 'Test User',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('home', absolute: false));
        $this->assertAuthenticated();

        // Verify user is created in database with auto-generated unique username
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'test', // slug of email prefix 'test'
        ]);
    }
}
