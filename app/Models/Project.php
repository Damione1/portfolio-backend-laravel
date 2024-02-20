<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'status',
        'cover_image_id',
    ];

    protected $hidden = [
        'cover_image_id',
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skill', 'project_id', 'skill_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'cover_image_id');
    }


    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (request()->route('user_id')) {
                $builder->where('user_id',  intval(request()->route('user_id')))
                    ->where('status', 'published');
            } else {
                $builder->where('user_id', Auth::id());
            }
        });
    }
}
