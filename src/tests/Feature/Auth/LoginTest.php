<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_login_with_email_and_password()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();

        $this->assertArrayHasKey('token', $response);
    }

    public function test_invaild_email_throw_an_error()
    {
        $this->postJson(route('user.login'), [
            'email' => 'jamiulewu24@gmail.com',
            'password' => 'password'
        ])->assertUnauthorized();
    }

    public function test_incorrect_password_throw_an_error()
    {
        $user = $this->createUser();

        $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'random'
        ])->assertUnauthorized();
    }
}