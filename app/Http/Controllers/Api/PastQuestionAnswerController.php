<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PastQuestionAnswerResource;
use App\Models\PastQuestion;
use App\Models\PastQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PastQuestionAnswerController extends Controller
{
    /**
     * List answers (PDFs) for a published past question.
     */
    public function index(PastQuestion $pastQuestion)
    {
        abort_unless($pastQuestion->is_published, 404);

        $answers = $pastQuestion->answers()->orderBy('created_at')->get();

        return PastQuestionAnswerResource::collection($answers);
    }

    /**
     * Download an answer PDF for a published past question.
     */
    public function download(PastQuestion $pastQuestion, PastQuestionAnswer $answer)
    {
        abort_unless($pastQuestion->is_published, 404);

        if ($answer->past_question_id !== $pastQuestion->id) {
            abort(404, 'Answer not found for this past question.');
        }

        if (! Storage::disk('public')->exists($answer->file_path)) {
            abort(404, 'File not found');
        }

        $fullPath = Storage::disk('public')->path($answer->file_path);

        return response()->download($fullPath, basename($fullPath), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
