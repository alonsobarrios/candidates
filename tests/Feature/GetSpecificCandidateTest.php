<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetSpecificCandidateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_specific_candidate()
    {
        $user = User::factory()->create();
        $this->assertModelExists($user);

        $response_auth = $this->post('/api/auth', ['username' => $user->username, 'password' => 'Cl4v3M4n4g3r']);
        $response_auth->assertStatus(200);

        $candidate = Candidate::factory()->create();
        $this->assertModelExists($candidate);

        $response = $this->get('/api/lead/'.$candidate->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'meta' => [ 
                        'success',
                        'errors'
                    ],
                    'data' => [
                        'id',
                        'name',
                        'source',
                        'owner',
                        'created_at',
                        'created_by'
                    ]
                ]);
    }
}
