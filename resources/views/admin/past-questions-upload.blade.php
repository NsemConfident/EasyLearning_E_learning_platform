<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Past Question (Direct Upload)</title>
    <style>
        body { font-family: system-ui, sans-serif; }
        .input { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; width: 100%; box-sizing: border-box; }
        .btn-primary { background: #d97706; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; cursor: pointer; font-size: 0.875rem; }
        .btn-primary:hover { background: #b45309; }
        .btn-secondary { background: white; color: #374151; padding: 0.5rem 1rem; border-radius: 0.375rem; border: 1px solid #d1d5db; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.875rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.25rem; color: #374151; }
        .mt-1 { margin-top: 0.25rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .grid-cols-2 { display: grid; gap: 1rem; }
        @media (min-width: 640px) { .sm\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); } }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .pt-2 { padding-top: 0.5rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .border { border-width: 1px; }
        .bg-white { background: white; }
        .p-6 { padding: 1.5rem; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
        .text-sm { font-size: 0.875rem; }
        .text-2xl { font-size: 1.5rem; }
        .font-semibold { font-weight: 600; }
        .text-gray-600 { color: #4b5563; }
        .text-primary-600 { color: #d97706; }
        .bg-green-50 { background: #f0fdf4; }
        .text-green-800 { color: #166534; }
        .bg-red-50 { background: #fef2f2; }
        .text-red-800 { color: #991b1b; }
        .max-w-2xl { max-width: 42rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .bg-gray-50 { background-color: #f9fafb; }
        .p-4 { padding: 1rem; }
        .rounded-md { border-radius: 0.375rem; }
        .list-inside { list-style-position: inside; }
        .list-disc { list-style-type: disc; }
    </style>
</head>
<body class="bg-gray-50 p-6">
<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Add Past Question (Direct Upload)</h1>
        <a href="{{ url('/admin/past-questions') }}" class="text-sm text-primary-600 hover:underline">← Back to Past Questions</a>
    </div>
    <p class="text-sm text-gray-600">Use this form if the normal Create form fails to upload the PDF. The file is sent with the form instead of a separate upload step.</p>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 text-red-800">
            <ul class="list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.past-questions.direct-upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-lg border bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject *</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700">Level *</label>
                <input type="text" name="level" id="level" value="{{ old('level') }}" placeholder="e.g. O-Level, A-Level" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year *</label>
                <input type="text" name="year" id="year" value="{{ old('year') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">— None —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label for="file_path" class="block text-sm font-medium text-gray-700">PDF File * (max 20 MB)</label>
            <input type="file" name="file_path" id="file_path" accept=".pdf,application/pdf" required class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-primary-700 hover:file:bg-primary-100">
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            <label for="is_published" class="text-sm font-medium text-gray-700">Published</label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">Create Past Question</button>
            <a href="{{ url('/admin/past-questions') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
