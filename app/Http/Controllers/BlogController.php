<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.blog-index', [
            'posts' => Post::published()->with('author')
                ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->input('q').'%'))
                ->paginate(9)->withQueryString(),
            'breadcrumbs' => [['label' => 'مجله دیجی‌نو', 'url' => route('blog.index')]],
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->incrementQuietly('views_count');

        return view('pages.blog-show', [
            'post' => $post->load('author', 'category'),
            'related' => Post::published()->whereKeyNot($post->id)->limit(3)->get(),
            'breadcrumbs' => [
                ['label' => 'مجله دیجی‌نو', 'url' => route('blog.index')],
                ['label' => $post->title, 'url' => route('blog.show', $post)],
            ],
        ]);
    }
}
