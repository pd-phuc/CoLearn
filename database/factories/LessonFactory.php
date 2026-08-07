<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'section_id' => Section::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => 'video',
            'video_url' => 'courses/sample-video.mp4',
            'document_path' => null,
            'content' => fake()->paragraph(),
            'duration' => fake()->numberBetween(180, 1800), // 3 mins to 30 mins
            'is_free_preview' => fake()->boolean(20), // 20% preview lessons
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
