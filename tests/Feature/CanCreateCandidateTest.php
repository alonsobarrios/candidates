<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanCreateCandidateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     *
     * @return void
     */
    public function test_can_create_candidate()
    {
        $user = User::factory()->create();
        $this->assertModelExists($user);
        $this->assertEquals('manager', $user->role);

        $response_auth = $this->post('/api/auth', ['username' => $user->username, 'password' => 'Cl4v3M4n4g3r']);
        $response_auth->assertStatus(200)
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

        $userAgent = User::factory()->create(['role' => 'agent']);
        $this->assertModelExists($userAgent);
        $this->assertEquals('agent', $userAgent->role);

        $data = [
            'name' => 'Mi candidato',
            'source' => 'Fotocasa',
            'owner' => $userAgent->id
        ];
        $response = $this->post('/api/lead', $data);

        $response->assertStatus(201)
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
