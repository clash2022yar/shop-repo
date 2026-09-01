<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with(['product.images', 'user'])
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
                ->when($request->filled('q'), fn ($q) => $q->where('body', 'like', '%'.$request->input('q').'%'))
                ->when($request->filled('rating'), fn ($q) => $q->where('rating', $request->input('rating')))
                ->latest()->paginate(config('digino.admin.per_page'))->withQueryString(),
            'counts' => [
                'all' => Review::count(),
                'pending' => Review::pending()->count(),
                'approved' => Review::approved()->count(),
                'rejected' => Review::where('status', ReviewStatus::Rejected->value)->count(),
            ],
        ]);
    }

    public function show(Review $review)
    {
        return $this->ok('', [
            'html' => view('admin.reviews.partials.detail', [
                'review' => $review->load('product', 'user'),
            ])->render(),
        ]);
    }

    public function approve(Review $review)
    {
        $review->update(['status' => ReviewStatus::Approved->value, 'reject_reason' => null]);

        return $this->ok('دیدگاه تأیید و منتشر شد.', ['status' => 'approved']);
    }

    public function reject(Request $request, Review $review)
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $review->update([
            'status' => ReviewStatus::Rejected->value,
            'reject_reason' => $data['reason'] ?? null,
        ]);

        return $this->ok('دیدگاه رد شد.', ['status' => 'rejected']);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return $this->ok('دیدگاه حذف شد.');
    }

    // ----------------------------------------------------------- questions
    public function questions(Request $request)
    {
        return view('admin.reviews.questions', [
            'questions' => Question::with(['product', 'user', 'answers.user'])
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
                ->latest()->paginate(config('digino.admin.per_page'))->withQueryString(),
            'counts' => [
                'all' => Question::count(),
                'pending' => Question::where('status', 'pending')->count(),
                'approved' => Question::where('status', 'approved')->count(),
            ],
        ]);
    }

    public function answer(Request $request, Question $question)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ], ['body.required' => 'متن پاسخ را بنویسید.']);

        $question->answers()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'is_staff' => true,
            'status' => 'approved',
        ]);

        $question->update(['status' => 'approved']);

        return $this->ok('پاسخ کارشناس دیجی‌نو ثبت شد.');
    }

    public function approveQuestion(Question $question)
    {
        $question->update(['status' => 'approved']);

        return $this->ok('پرسش تأیید شد.');
    }

    public function destroyQuestion(Question $question)
    {
        $question->delete();

        return $this->ok('پرسش حذف شد.');
    }
}
