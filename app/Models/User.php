<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'role', 'avatar', 'national_code',
        'birthday', 'gender', 'is_active', 'newsletter', 'loyalty_points',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'birthday' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'newsletter' => 'boolean',
            'role' => UserRole::class,
        ];
    }

    // ---------------------------------------------------------- relations
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishedProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // ---------------------------------------------------------- accessors
    public function isAdmin(): bool
    {
        return $this->role->canEnterAdmin();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/u', trim((string) $this->name)) ?: [];

        return mb_substr($parts[0] ?? '؟', 0, 1).(isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '');
    }

    public function getDefaultAddressAttribute(): ?Address
    {
        return $this->addresses->firstWhere('is_default', true) ?? $this->addresses->first();
    }

    // ------------------------------------------------------------ scopes
    public function scopeCustomers($q)
    {
        return $q->where('role', UserRole::Customer->value);
    }

    public function scopeStaff($q)
    {
        return $q->whereIn('role', [UserRole::Admin->value, UserRole::Manager->value]);
    }

    public function scopeSearch($q, ?string $term)
    {
        if (! $term) {
            return $q;
        }

        return $q->where(fn ($s) => $s->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('mobile', 'like', "%{$term}%"));
    }
}
