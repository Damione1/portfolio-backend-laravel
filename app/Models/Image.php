<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'filename',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'cover_image');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'image');
    }


    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (request()->route('user_id')) {
                $builder->where('user_id', request()->route('user_id'));
            } else {
                $builder->where('user_id', Auth::id());
            }
        });
    }
}
