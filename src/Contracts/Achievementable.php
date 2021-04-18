<?php

namespace tizis\achievements\Contracts;

interface HasAchievements
{
    public function achievements(): \Illuminate\Database\Eloquent\Relations\BelongsToMany;

    public function hasAchievement(Achievement $achievement);

    public function getAchievementsOfGroup(Group $group): \Illuminate\Database\Eloquent\Collection;
}