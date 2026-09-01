<?php

/*
|--------------------------------------------------------------------------
| Digino application settings
|--------------------------------------------------------------------------
|
| Business level configuration for the Digino store. Anything that a store
| operator may reasonably want to change without touching code lives here
| (or, when it should be editable at runtime, in the `settings` table which
| is managed from Admin » Settings).
|
*/

return [

    /**
     * Bumped on every release; appended to asset URLs for cache busting.
     */
    'version' => '1.0.0',

    'brand' => [
        'name' => 'دیجی‌نو',
        'name_en' => 'Digino',
        'tagline' => 'خرید هوشمند',
        'creator' => 'یارمحمدی',
    ],

    'currency' => [
        'code' => 'IRT',
        'label' => 'تومان',
        // Prices are stored in Toman as integers. No floating point money.
        'decimals' => 0,
    ],

    'catalog' => [
        'per_page' => 24,
        'related_limit' => 12,
        'recently_viewed_limit' => 12,
        'max_compare' => 4,
        'low_stock_threshold' => 5,
    ],

    'cart' => [
        'session_key' => 'digino.cart',
        'max_qty_per_item' => 5,
    ],

    'checkout' => [
        'free_shipping_from' => 5_000_000,
        'default_shipping_cost' => 49_000,
        'tax_percent' => 0,
    ],

    'search' => [
        'suggest_limit' => 8,
        'min_chars' => 2,
    ],

    'admin' => [
        'per_page' => 20,
    ],
];
