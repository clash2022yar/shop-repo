<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('shop:admin', function () {
    $name = $this->ask('Name');
    $email = $this->ask('Email');
    $password = $this->secret('Password (at least 12 characters)');
    if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
        $this->error('Valid email and a password of at least 12 characters are required.');

        return 1;
    }
    $user = User::firstOrNew(['email' => $email]);
    $user->name = $name;
    $user->password = $password;
    $user->is_admin = true;
    $user->save();
    $this->info('Administrator saved.');
})->purpose('Create or update a shop administrator interactively');
