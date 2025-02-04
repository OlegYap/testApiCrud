<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;


class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo_id'
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function getAverageRating(): float
    {
        return $this->comments()->avg('rating');
    }

    public static function getTopRated(int $limit = 10): Collection
    {
        return self::withAvg('comments', 'rating')
            ->orderByDesc('comments_avg_rating')
            ->take($limit)
            ->get();
    }

    public function getCompanyComments(): Collection
    {
        return $this->comments;
    }
}
