<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');
        Blade::directive('money', fn ($e) => "<?php echo strtr(number_format((float)($e)), ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹',','=>'٬']); ?>");
        Blade::directive('datefa', fn ($e) => "<?php echo \App\Support\Persian::date($e); ?>");
        View::composer(['store.*', 'account.*', 'admin.*', 'layouts.*', 'partials.*'], function ($view) {
            static $categories = null,$settings = null;
            $categories ??= Category::withCount(['products' => fn ($q) => $q->where('active', true)])->get();
            $settings ??= Setting::pluck('value', 'key');
            $view->with('navCategories', $categories)->with('shopSettings', $settings);
        });
    }
}
