<?php

namespace Database\Factories;

use App\Enums\UserRole;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        $name = fake()->randomElement(UserSeeder::FIRST_NAMES).' '.fake()->randomElement(UserSeeder::LAST_NAMES);

        return [
            'name' => $name,
            'email' => Str::lower(Str::random(10)).'@digino.test',
            'mobile' => '09'.fake()->numberBetween(10, 39).fake()->numerify('#######'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::Customer->value,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => UserRole::Admin->value]);
    }

    public function manager(): static
    {
        return $this->state(fn () => ['role' => UserRole::Manager->value]);
    }
}
