<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonUserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LessonController extends Controller
{
    public function complete(Request $request, Lesson $lesson)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        LessonUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => Carbon::now(),
            ]
        );

        return response()->json([
            'message' => 'Lesson marked as completed',
        ]);
    }
}
