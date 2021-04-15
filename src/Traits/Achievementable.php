<?php

namespace tizis\achievements\Traits;

use tizis\achievements\Contracts\Achievement;
use tizis\achievements\Contracts\Group;

trait Achievementable
{
    public function achievements(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(config('achievements.models.achievement'), 'achievements_progress', 'user_id', 'achievement_id')
            ->as('progress')
            ->withPivot(['points', 'id', 'unlocked_at'])
            ->withTimestamps();
    }

    public function hasAchievement(Achievement $achievement)
    {
        return $this->achievements()->wherePivot('achievement_id', $achievement->id)->first();
        //->wherePivot('unlocked_at', '!=', null);
    }

    public function getAchievementsOfGroup(Group $group): \Illuminate\Database\Eloquent\Collection
    {
        return $this->achievements()->where('group_id', $group->id)->get();
    }
}