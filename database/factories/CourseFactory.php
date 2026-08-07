<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'teacher_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomElement([499000, 699000, 999000, 1299000]),
            'discount_price' => fake()->randomElement([299000, 399000, 499000, null]),
            'thumbnail' => null,
            'status' => 'published',
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
        ];
    }
}
