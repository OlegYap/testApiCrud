<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'original_name',
        'mime_type',
        'size'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'avatar_id');
    }

}
