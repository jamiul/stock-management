<?php

namespace Tests;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $user = $user ?: $this->createUser();
        Sanctum::actingAs($user);

        return $user;
    }

    // register a new user
    public function createUser($override = [])
    {
        return User::factory()->create($override);
    }
}