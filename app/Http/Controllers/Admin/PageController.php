<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('sort_order')->get(),
        ]);
    }

    public function show(Page $page)
    {
        return $this->ok('', ['page' => $page]);
    }

    public function store(Request $request)
    {
        $page = Page::create($this->validated($request));

        return $this->ok('صفحه ساخته شد.', ['id' => $page->id]);
    }

    public function update(Request $request, Page $page)
    {
        $page->update($this->validated($request, $page));

        return $this->ok('صفحه به‌روزرسانی شد.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return $this->ok('صفحه حذف شد.');
    }

    protected function validated(Request $request, ?Page $page = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('pages')->ignore($page?->id)],
            'body' => ['nullable', 'string', 'max:50000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], ['title.required' => 'عنوان صفحه را وارد کنید.']) + [
            'is_published' => $request->boolean('is_published'),
            'in_footer' => $request->boolean('in_footer'),
        ];
    }
}
