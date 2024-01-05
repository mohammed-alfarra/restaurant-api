<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'level' => 0,
        ];
    }

    public function withSubcategory()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => randomOrCreateFactory(Category::class),
            ];
        });
    }
}
