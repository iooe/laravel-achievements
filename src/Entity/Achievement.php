<?php

namespace tizis\achievements\Entity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model implements \tizis\achievements\Contracts\Achievement
{
    protected $table = 'achievements';

    public $timestamps = false;

    protected $fillable = [
        'title', 'level', 'description', 'group_id', 'points', 'value'
    ];

    public function completedProgress()
    {
        return $this->progress()->unlocked();
    }

    public function progress(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(config('achievements.models.progress'));
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('achievements.models.group'));
    }

    public function scopeSingleOnly(Builder $query): Builder
    {
        return $query->whereNull('group_id');
    }

    public function isSingle(): bool
    {
        return $this->attributes['group_id'] === null;
    }
}
