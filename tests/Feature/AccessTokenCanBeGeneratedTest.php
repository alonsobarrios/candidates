<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessTokenCanBeGeneratedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     *
     * @return void
     */
    public function test_access_token_can_be_generated()
    {
        $user = User::factory()->create();
        $this->assertModelExists($user);
        $response = $this->post('/api/auth', ['username' => $user->username, 'password' => 'Cl4v3M4n4g3r']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => [ 
                    'success',
                    'errors'
                ],
                'data' => [
                    'token',
                    'minutes_to_expire'
                ]
            ]);
    }
}
