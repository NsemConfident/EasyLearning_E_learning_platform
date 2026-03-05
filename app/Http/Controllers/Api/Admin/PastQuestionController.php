<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PastQuestionResource;
use App\Models\Category;
use App\Models\PastQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PastQuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:10'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('type', Category::TYPE_PAST_QUESTION),
            ],
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $file = $validated['file'];
        $path = $file->store('past-questions', 'public');

        $pastQuestion = PastQuestion::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'subject' => $validated['subject'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'category_id' => $validated['category_id'] ?? null,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'is_published' => $validated['is_published'] ?? false,
            'created_by' => $request->user()->id,
        ]);

        return new PastQuestionResource($pastQuestion);
    }

    public function update(Request $request, PastQuestion $pastQuestion)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'subject' => ['sometimes', 'string', 'max:255'],
            'level' => ['sometimes', 'string', 'max:255'],
            'year' => ['sometimes', 'string', 'max:10'],
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('type', Category::TYPE_PAST_QUESTION),
            ],
            'file' => ['sometimes', 'file', 'mimes:pdf', 'max:20480'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('file')) {
            if ($pastQuestion->file_path) {
                Storage::disk('public')->delete($pastQuestion->file_path);
            }

            $file = $validated['file'];
            $path = $file->store('past-questions', 'public');

            $pastQuestion->file_path = $path;
            $pastQuestion->file_size = $file->getSize();
        }

        $pastQuestion->fill(collect($validated)->except('file')->toArray());
        $pastQuestion->save();

        return new PastQuestionResource($pastQuestion);
    }

    public function destroy(PastQuestion $pastQuestion)
    {
        if ($pastQuestion->file_path) {
            Storage::disk('public')->delete($pastQuestion->file_path);
        }

        $pastQuestion->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
