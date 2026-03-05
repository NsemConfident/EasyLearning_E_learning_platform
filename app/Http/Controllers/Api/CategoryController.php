<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * List categories, optionally filtered by content type.
     * Query: type=course|past_question (required for clarity in the app).
     */
    public function index(Request $request)
    {
        $request->validate([
            'type' => ['nullable', 'string', 'in:course,past_question'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Category::query()->orderBy('type')->orderBy('name');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $perPage = $request->integer('per_page', 15);
        $perPage = min(max($perPage, 1), 100);

        $categories = $query->paginate($perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * Show a single category.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
}
