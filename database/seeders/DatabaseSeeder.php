<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = User::factory(10)->create();

        $companies = Company::factory(10)->create();

        foreach ($companies as $company) {
            foreach ($users as $user) {
                Comment::factory()->create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'commentable_type' => Company::class,
                    'commentable_id' => $company->id
                ]);
            }
        }
    }
}
