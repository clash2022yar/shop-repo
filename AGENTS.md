# Working on Digino

- PHP 8.3.28 deployment target, Laravel 13, Blade and vanilla browser JavaScript.
- Do not introduce Vite, React, Vue, Alpine, Livewire, jQuery or frontend frameworks.
- Compile Tailwind with `npm run build`; commit public/assets/tailwind.css.
- Shared layouts and partials live in resources/views/layouts and partials.
- All writes require authentication where appropriate, CSRF, validation and ownership checks.
- Keep business logic for stock/orders in app/Services; never trust client prices.
- Money is integer toman. Dates render through App\Support\Persian.
- Test with `php vendor/bin/phpunit`, `php artisan view:cache`, and `composer validate`.
- Never commit .env, credentials, database files, vendor, node_modules or runtime uploads.
- See README for runtime limitations and honest external-service boundaries.
