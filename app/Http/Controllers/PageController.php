<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'stats' => [
                'products' => Product::active()->count(),
                'customers' => User::customers()->count(),
                'orders' => Order::count(),
                'brands' => \App\Models\Brand::active()->count(),
            ],
            'breadcrumbs' => [['label' => 'درباره دیجی‌نو', 'url' => route('about')]],
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'breadcrumbs' => [['label' => 'تماس با ما', 'url' => route('contact')]],
        ]);
    }

    public function faq()
    {
        return view('pages.faq', [
            'breadcrumbs' => [['label' => 'پرسش‌های متداول', 'url' => route('faq')]],
        ]);
    }

    public function terms()
    {
        return view('pages.terms', [
            'breadcrumbs' => [['label' => 'شرایط استفاده', 'url' => route('terms')]],
        ]);
    }

    public function privacy()
    {
        return view('pages.privacy', [
            'breadcrumbs' => [['label' => 'حریم خصوصی', 'url' => route('privacy')]],
        ]);
    }

    public function show(Page $page)
    {
        abort_unless($page->is_published, 404);

        return view('pages.static', [
            'page' => $page,
            'breadcrumbs' => [['label' => $page->title, 'url' => route('pages.show', $page)]],
        ]);
    }
}
