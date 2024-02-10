<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'order',
        'image_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_skill', 'skill_id', 'project_id');
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'experience_skill', 'skill_id', 'experience_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
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
