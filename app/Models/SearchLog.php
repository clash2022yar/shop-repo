<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = ['term', 'results_count', 'user_id'];

    /** Most searched terms — powers the "trending searches" list. */
    public static function trending(int $limit = 8): array
    {
        return static::query()
            ->selectRaw('term, COUNT(*) as hits')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('term')
            ->orderByDesc('hits')
            ->limit($limit)
            ->pluck('term')
            ->all();
    }
}
