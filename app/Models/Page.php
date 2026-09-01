<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'is_published', 'in_footer', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'in_footer' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::saving(function (self $page) {
            if (blank($page->slug)) {
                $page->slug = Str::slug($page->title, '-', 'fa') ?: 'page-'.Str::random(6);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }
}
