<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllCandidatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_candidates()
    {
        $user = User::factory()->create();
        $this->assertModelExists($user);

        $response_auth = $this->post('/api/auth', ['username' => $user->username, 'password' => 'Cl4v3M4n4g3r']);
        $response_auth->assertStatus(200);

        User::factory(15)->state(new Sequence(
            ['role' => 'manager'],
            ['role' => 'agent'],
        ))->hasCandidates(50)->create();
        
        $response = $this->get('/api/leads');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'meta' => [ 
                        'success',
                        'errors'
                    ],
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'source',
                            'owner',
                            'created_at',
                            'created_by'
                        ]
                    ]
                ]);
    }
}
