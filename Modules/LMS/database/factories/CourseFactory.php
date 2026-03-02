<?php

namespace Modules\LMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\LMS\Models\Category;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\LMS\Models\Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id,
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(3),
            'thumbnail' => $this->faker->imageUrl(640, 480, 'cats'),
            'description' => $this->faker->paragraph(10),
        ];
    }
}

