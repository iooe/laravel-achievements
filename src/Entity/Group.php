<?php

namespace tizis\achievements\Entity;

use Illuminate\Database\Eloquent\Model;

class Group extends Model implements \tizis\achievements\Contracts\Group
{
    protected $table = 'achievements_groups';

    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'hash', 'namespace_id', 'meta'
    ];

    protected $withCount = ['achievements'];

    public function namespace(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('achievements.models.group_namespace'));
    }

    public function levels(): int
    {
        if ($this->achievements_count) {
            return $this->achievements_count;
        }

        return $this->achievements()->count();
    }

    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(config('achievements.models.achievement'))->orderBy('level');
    }
}
