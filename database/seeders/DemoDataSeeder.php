<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\PastQuestion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'role' => 'admin',
            ],
        );

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Demo Student',
                'password' => 'password',
                'role' => 'student',
            ],
        );

        $course = Course::firstOrCreate(
            ['title' => 'Intro to E-Learning'],
            [
                'description' => 'Sample course for EASYLEARNING.',
                'thumbnail' => null,
                'price' => 0,
                'instructor_name' => 'John Instructor',
                'is_published' => true,
            ],
        );

        $module = Module::firstOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Getting Started',
            ],
            [
                'order' => 1,
            ],
        );

        Lesson::firstOrCreate(
            [
                'module_id' => $module->id,
                'title' => 'Welcome Lesson',
            ],
            [
                'video_url' => 'https://example.com/video.mp4',
                'duration' => 600,
                'order' => 1,
            ],
        );

        PastQuestion::factory()->count(5)->create([
            'created_by' => $admin->id,
            'is_published' => true,
        ]);
    }
}
