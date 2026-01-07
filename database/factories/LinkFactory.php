<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Link;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'short_code' => $this->faker->unique()->regexify('[A-Za-z0-9]{6}'),
            'original_url' => $this->faker->url(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'is_active' => true,
            'ad_type' => 'no_ads',
            'is_monetized' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
