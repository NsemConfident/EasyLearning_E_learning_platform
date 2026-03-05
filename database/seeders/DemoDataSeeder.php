<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\PastQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
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

        // Course categories (for courses)
        $courseCategories = [
            ['name' => 'HTML', 'slug' => 'html', 'description' => 'HTML and markup'],
            ['name' => 'CSS', 'slug' => 'css', 'description' => 'Styling and layout'],
            ['name' => 'Frontend', 'slug' => 'frontend', 'description' => 'Frontend development'],
            ['name' => 'Data Analysis', 'slug' => 'data-analysis', 'description' => 'Data analysis and analytics'],
            ['name' => 'Project Management', 'slug' => 'project-management', 'description' => 'Project management'],
            ['name' => 'General Education', 'slug' => 'general-education', 'description' => 'General courses'],
        ];
        $categoryCourse = null;
        foreach ($courseCategories as $i => $attrs) {
            $cat = Category::firstOrCreate(
                ['type' => Category::TYPE_COURSE, 'slug' => $attrs['slug']],
                array_merge($attrs, ['type' => Category::TYPE_COURSE]),
            );
            if ($i === array_key_last($courseCategories)) {
                $categoryCourse = $cat;
            }
        }

        // Past question categories (for past questions)
        $pastQuestionCategories = [
            ['name' => 'GCE Ordinary Level', 'slug' => 'gce-ordinary-level', 'description' => 'GCE O-Level past questions'],
            ['name' => 'GCE Advanced Level', 'slug' => 'gce-advanced-level', 'description' => 'GCE A-Level past questions'],
            ['name' => 'HND', 'slug' => 'hnd', 'description' => 'Higher National Diploma'],
            ['name' => 'Police Council', 'slug' => 'police-council', 'description' => 'Police Council exams'],
            ['name' => 'Polytechnic', 'slug' => 'polytechnic', 'description' => 'Polytechnic past questions'],
        ];
        $categoryPastQuestions = null;
        foreach ($pastQuestionCategories as $i => $attrs) {
            $cat = Category::firstOrCreate(
                ['type' => Category::TYPE_PAST_QUESTION, 'slug' => $attrs['slug']],
                array_merge($attrs, ['type' => Category::TYPE_PAST_QUESTION]),
            );
            if ($i === 0) {
                $categoryPastQuestions = $cat;
            }
        }

        $course = Course::firstOrCreate(
            ['title' => 'Intro to E-Learning'],
            [
                'category_id' => $categoryCourse->id,
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
            'category_id' => $categoryPastQuestions->id,
            'created_by' => $admin->id,
            'is_published' => true,
        ]);
    }
}
