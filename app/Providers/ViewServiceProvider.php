<?php

namespace App\Providers;

use App\Enums\OrderStatus;
use App\Enums\ReviewStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;
use App\Models\Ticket;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Wires up the pieces every page shares: the mega-menu categories, footer
 * links and a handful of Blade helpers used across the templates.
 */
class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::defaultView('components.pagination');
        Paginator::defaultSimpleView('components.pagination');

        // ---- Shared data for the header & footer partials --------------
        View::composer(['partials.header', 'partials.mega-menu', 'partials.footer'], function ($view) {
            $view->with('menuCategories', cache_remember_short('digino.menu', fn () => Category::query()
                ->active()->inMenu()->roots()
                ->with(['children' => fn ($q) => $q->active()->inMenu()->with('children')])
                ->orderBy('sort_order')
                ->get()));

            $view->with('footerPages', cache_remember_short('digino.footer-pages', fn () => Page::query()
                ->published()->where('in_footer', true)->orderBy('sort_order')->get()));
        });

        // ---- Mobile drawer needs the same category tree ----------------
        View::composer('partials.mobile-nav', function ($view) {
            $view->with('menuCategories', cache_remember_short('digino.menu', fn () => Category::query()
                ->active()->inMenu()->roots()
                ->with(['children' => fn ($q) => $q->active()->inMenu()->with('children')])
                ->orderBy('sort_order')
                ->get()));
        });

        // ---- Counters shown next to the account sidebar links ----------
        View::composer('partials.account-sidebar', function ($view) {
            $user = auth()->user();

            $view->with('sidebarCounts', $user === null ? [] : [
                'orders' => $user->orders()->whereNotIn('status', ['delivered', 'cancelled'])->count(),
                'wishlist' => $user->wishlists()->count(),
                'reviews' => $user->reviews()->count(),
                'addresses' => $user->addresses()->count(),
                'notifications' => $user->notifications()->whereNull('read_at')->count(),
                'tickets' => $user->tickets()->where('status', '!=', 'closed')->count(),
            ]);
        });

        // ---- Pending-work counters for the admin panel -----------------
        View::composer(['partials.admin-sidebar', 'layouts.admin'], function ($view) {
            $view->with('adminBadges', cache_remember_short('digino.admin-badges', fn () => [
                'new_orders' => Order::where('status', OrderStatus::Pending->value)->count(),
                'pending_reviews' => Review::where('status', ReviewStatus::Pending->value)->count(),
                'pending_questions' => Question::whereNull('answer')->count(),
                'open_tickets' => Ticket::whereIn('status', ['open', 'answered'])->count(),
                'low_stock' => Product::active()
                    ->where('stock', '<=', config('digino.catalog.low_stock_threshold'))
                    ->count(),
            ], 60));
        });

        // ---- Blade helpers ---------------------------------------------
        Blade::directive('toman', fn ($expr) => "<?php echo fa_number(toman($expr)); ?>");
        Blade::directive('fa', fn ($expr) => "<?php echo fa_number($expr); ?>");
        Blade::directive('jalali', fn ($expr) => "<?php echo jalali($expr); ?>");

        // @admin ... @endadmin
        Blade::if('admin', fn () => auth()->check() && auth()->user()->isAdmin());
        Blade::if('superadmin', fn () => auth()->check() && auth()->user()->isSuperAdmin());
    }
}
