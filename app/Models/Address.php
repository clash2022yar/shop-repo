<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'label', 'receiver_name', 'receiver_mobile', 'province', 'city',
        'line', 'plate', 'unit', 'postal_code', 'is_default',
    ];

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }

    protected static function booted(): void
    {
        // Exactly one default address per user.
        static::saved(function (self $address) {
            if ($address->is_default) {
                static::where('user_id', $address->user_id)
                    ->whereKeyNot($address->getKey())
                    ->update(['is_default' => false]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAttribute(): string
    {
        return collect([
            $this->province,
            $this->city,
            $this->line,
            $this->plate ? 'پلاک '.$this->plate : null,
            $this->unit ? 'واحد '.$this->unit : null,
        ])->filter()->implode('، ');
    }
}
