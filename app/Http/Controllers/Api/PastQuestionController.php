<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PastQuestionResource;
use App\Models\Category;
use App\Models\PastQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PastQuestionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('type', Category::TYPE_PAST_QUESTION)],
        ]);

        $query = PastQuestion::query()
            ->where('is_published', true)
            ->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $pastQuestions = $query->latest()->paginate(10);

        return PastQuestionResource::collection($pastQuestions);
    }

    public function show(PastQuestion $pastQuestion)
    {
        abort_unless($pastQuestion->is_published, 404);

        $pastQuestion->load('category');

        return new PastQuestionResource($pastQuestion);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string'],
            'subject' => ['nullable', 'string'],
            'year' => ['nullable', 'string'],
            'level' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('type', Category::TYPE_PAST_QUESTION)],
        ]);

        $query = PastQuestion::query()
            ->where('is_published', true)
            ->with('category');

        if (! empty($validated['category_id'] ?? null)) {
            $query->where('category_id', $validated['category_id']);
        }

        if (! empty($validated['title'] ?? null)) {
            $query->where('title', 'like', '%'.$validated['title'].'%');
        }

        if (! empty($validated['subject'] ?? null)) {
            $query->where('subject', $validated['subject']);
        }

        if (! empty($validated['year'] ?? null)) {
            $query->where('year', $validated['year']);
        }

        if (! empty($validated['level'] ?? null)) {
            $query->where('level', $validated['level']);
        }

        $results = $query->latest()->paginate(10);

        return PastQuestionResource::collection($results);
    }

    public function download(PastQuestion $pastQuestion)
    {
        abort_unless($pastQuestion->is_published, 404);

        if (! Storage::disk('public')->exists($pastQuestion->file_path)) {
            abort(404, 'File not found');
        }

        $pastQuestion->increment('download_count');

        $fullPath = Storage::disk('public')->path($pastQuestion->file_path);

        return response()->download($fullPath, basename($fullPath), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
