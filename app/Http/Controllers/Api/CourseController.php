<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\LessonUserProgress;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query()
            ->where('is_published', true)
            ->paginate(10);

        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {
        abort_unless($course->is_published, 404);

        $course->load(['modules.lessons']);

        return new CourseResource($course);
    }

    public function enroll(Request $request, Course $course)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->courses()->syncWithoutDetaching([$course->id]);

        return response()->json([
            'message' => 'Enrolled successfully',
        ]);
    }

    public function progress(Request $request, Course $course)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $course->load('modules.lessons');

        $lessonIds = $course->modules->flatMap->lessons->pluck('id');
        $totalLessons = $lessonIds->count();

        $completedCount = LessonUserProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->whereNotNull('completed_at')
            ->count();

        $percentage = $totalLessons > 0
            ? round(($completedCount / $totalLessons) * 100, 2)
            : 0;

        $lastProgress = LessonUserProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->orderByDesc('completed_at')
            ->first();

        return response()->json([
            'course' => new CourseResource($course),
            'stats' => [
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedCount,
                'percentage_completed' => $percentage,
                'last_lesson_id' => $lastProgress?->lesson_id,
            ],
        ]);
    }
}
