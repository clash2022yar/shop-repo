<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.posts.index', [
            'posts' => Post::with('author')
                ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->input('q').'%'))
                ->latest()->paginate(config('digino.admin.per_page'))->withQueryString(),
            'counts' => [
                'all' => Post::count(),
                'published' => Post::where('is_published', true)->count(),
                'draft' => Post::where('is_published', false)->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.posts.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $post = Post::create($this->validated($request) + ['user_id' => $request->user()->id]);

        return $this->ok('مطلب منتشر شد.', ['redirect' => route('admin.posts.index'), 'id' => $post->id]);
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request, $post));

        return $this->ok('مطلب به‌روزرسانی شد.', ['redirect' => route('admin.posts.index')]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return $this->ok('مطلب حذف شد.');
    }

    protected function validated(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('posts')->ignore($post?->id)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:50000'],
            'cover' => ['nullable', 'string', 'max:255'],
            'read_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'published_at' => ['nullable', 'date'],
        ], [
            'title.required' => 'عنوان مطلب را وارد کنید.',
        ]) + [
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->input('published_at') ?: now(),
        ];
    }
}
