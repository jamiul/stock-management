<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // test user has many tasks
    public function test_create_a_new_user()
    {
        $user = $this->createUser();
        $this->assertInstanceOf(User::class, $user->first());
    }
}