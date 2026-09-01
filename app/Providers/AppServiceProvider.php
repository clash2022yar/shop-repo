<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CartService::class);
        $this->app->scoped(SearchService::class);
        $this->app->bind(CheckoutService::class);
    }

    public function boot(): void
    {
        // ---- Laravel 13 model hygiene ---------------------------------
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::automaticallyEagerLoadRelationships();
        Model::unguard(false);

        // ---- Security -------------------------------------------------
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => $this->app->isProduction()
            ? Password::min(8)->letters()->numbers()->uncompromised()
            : Password::min(6));

        // ---- Authorization --------------------------------------------
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);

        Gate::define('access-admin', fn ($user) => $user->isAdmin());
        Gate::define('manage-settings', fn ($user) => $user->isSuperAdmin());
        Gate::define('manage-staff', fn ($user) => $user->isSuperAdmin());

        // ---- Route bindings -------------------------------------------
        // Products are looked up by slug but must still be visible.
        \Illuminate\Support\Facades\Route::bind('product', function (string $value) {
            return Product::with(['brand', 'category.parent', 'images', 'variants', 'attributes'])
                ->where('slug', $value)
                ->firstOrFail();
        });
    }
}
