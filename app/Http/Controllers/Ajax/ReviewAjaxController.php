<?php

namespace App\Http\Controllers\Ajax;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAjaxController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $reviews = $product->approvedReviews()->with('user')
            ->when($request->input('sort') === 'helpful', fn ($q) => $q->reorder()->orderByDesc('likes'))
            ->paginate(6);

        return $this->ok('', [
            'html' => view('partials.review-list', compact('reviews'))->render(),
            'has_more' => $reviews->hasMorePages(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
            'pros' => ['nullable', 'array', 'max:5'],
            'pros.*' => ['string', 'max:120'],
            'cons' => ['nullable', 'array', 'max:5'],
            'cons.*' => ['string', 'max:120'],
            'recommends' => ['nullable', 'boolean'],
        ], [
            'rating.required' => 'امتیاز خود را انتخاب کنید.',
            'body.required' => 'متن دیدگاه را بنویسید.',
            'body.min' => 'متن دیدگاه باید حداقل ۱۰ نویسه باشد.',
        ]);

        if ($product->reviews()->where('user_id', $request->user()->id)->exists()) {
            return $this->fail('شما پیش‌تر برای این کالا دیدگاه ثبت کرده‌اید.');
        }

        // A review is marked as "buyer" only when the purchase really happened.
        $isBuyer = $request->user()->orders()
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->exists();

        Review::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'body' => $data['body'],
            'pros' => array_values(array_filter($data['pros'] ?? [])),
            'cons' => array_values(array_filter($data['cons'] ?? [])),
            'recommends' => $request->boolean('recommends', true),
            'is_buyer' => $isBuyer,
            'status' => ReviewStatus::Pending->value,
        ]);

        return $this->ok('دیدگاه شما ثبت شد و پس از بررسی منتشر می‌شود.');
    }

    public function vote(Request $request, Review $review)
    {
        $request->validate(['type' => ['required', 'in:like,dislike']]);

        // One vote per review per session.
        $key = 'review_votes';
        $voted = (array) session($key, []);

        if (in_array($review->id, $voted, true)) {
            return $this->fail('شما قبلاً به این دیدگاه رأی داده‌اید.');
        }

        $review->increment($request->input('type') === 'like' ? 'likes' : 'dislikes');
        session([$key => [...$voted, $review->id]]);

        return $this->ok('رأی شما ثبت شد.', [
            'likes' => $review->fresh()->likes,
            'dislikes' => $review->fresh()->dislikes,
        ]);
    }

    public function ask(Request $request, Product $product)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ], ['body.required' => 'متن پرسش را بنویسید.']);

        Question::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'status' => 'pending',
        ]);

        $product->increment('questions_count');

        return $this->ok('پرسش شما ثبت شد و پس از بررسی نمایش داده می‌شود.');
    }

    public function answer(Request $request, Question $question)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ], ['body.required' => 'متن پاسخ را بنویسید.']);

        $question->answers()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'is_staff' => $request->user()->isAdmin(),
            'status' => $request->user()->isAdmin() ? 'approved' : 'pending',
        ]);

        return $this->ok('پاسخ شما ثبت شد.');
    }
}
