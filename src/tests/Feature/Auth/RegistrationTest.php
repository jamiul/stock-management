<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // guest can create an account
    public function test_guest_can_register()
    {
        $this->postJson(route('user.register'), [
            'name' => "Jamiul",
            'email' => 'jamiulewu24@gmail.com',
            'password' => 'mypass123',
            'password_confirmation' => 'mypass123',
        ])->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'Jamiul']);
    }
}