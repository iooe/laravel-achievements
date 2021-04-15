<?php

namespace tizis\achievements\Entity;

use Illuminate\Database\Eloquent\Model;

class GroupNamespace extends Model implements \tizis\achievements\Contracts\GroupNamespace
{
    protected $table = 'achievements_groups_namespaces';

    public $timestamps = false;

    protected $fillable = [
        'title'
    ];

    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(config('achievements.models.achievement'))->orderBy('level');
    }

    public function groups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(config('achievements.models.group'), 'namespace_id');
    }
}
