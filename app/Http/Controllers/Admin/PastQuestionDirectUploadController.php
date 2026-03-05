<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PastQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Fallback direct upload for Past Questions when Filament's Livewire
 * temporary upload fails (e.g. PHP limits, temp dir permissions).
 * Form POSTs the file in the same request – no Livewire temp upload.
 */
class PastQuestionDirectUploadController extends Controller
{
    public function showForm(Request $request)
    {
        $this->authorizeAdmin($request);

        $categories = Category::forPastQuestions()->orderBy('name')->get();

        return view('admin.past-questions-upload', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

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
            'file_path' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $file = $request->file('file_path');
        $path = $file->store('past-questions', 'public');

        PastQuestion::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'subject' => $validated['subject'],
            'level' => $validated['level'],
            'year' => $validated['year'],
            'category_id' => $validated['category_id'] ?? null,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'is_published' => $request->boolean('is_published'),
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->to('/admin/past-questions')
            ->with('success', 'Past question created successfully.');
    }

    private function authorizeAdmin(Request $request): void
    {
        if (! $request->user()?->isAdmin()) {
            abort(403, 'Admin only.');
        }
    }
}
