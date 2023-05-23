<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->state(new Sequence(
            ['role' => 'manager'],
            ['role' => 'agent'],
        ))->hasCandidates(100)->create();
    }
}
