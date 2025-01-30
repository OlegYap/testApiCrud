<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $company = Company::factory()->create();

        return [
            'content' => fake()->text(300),
            'rating' => fake()->numberBetween(1, 10),
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'commentable_type' => Company::class,
            'commentable_id' => $company->id,
        ];
    }
}
