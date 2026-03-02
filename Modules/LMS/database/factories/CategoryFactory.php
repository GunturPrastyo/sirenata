<?php

namespace Modules\LMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\LMS\Models\Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $categories = [
            'Praktik',
            'Perpres',
            'Teori',
            'Perkiraan',
            'Perencanaan',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
        ];
    }
}

