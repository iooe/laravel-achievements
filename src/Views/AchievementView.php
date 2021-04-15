<?php

namespace tizis\achievements\Views;

use Illuminate\Database\Eloquent\Model;
use tizis\achievements\Contracts\Group;

class AchievementView
{
    protected ?Model $user;

    public function setUser(?Model $user): void
    {
        $this->user = $user;
    }

    public function getIndexViewParams()
    {
        $namespaces = config('achievements.models.group_namespace')::with('groups')->get();

        return compact('namespaces');
    }

    private function getUserProgress(array $achievementsIds)
    {
        return config('achievements.models.progress')::where('user_id', $this->user->id)
            ->whereIn('achievement_id', $achievementsIds)
            ->get();
    }

    public function getGroupViewParams(Group $group)
    {
        if ($this->user) {
            $myProgressCollection = $this->getUserProgress($group->achievements->pluck('id')->toArray());
        }

        foreach ($group->achievements as $achievement) {
            $achievement
                ->loadCount('completedProgress as progress_count');

            if (!isset($myProgressCollection)) {
                $achievement->my_progress = 0;
                continue;
            }

            $progress = $myProgressCollection->where('achievement_id', $achievement->id)->first();

            if (!$progress) {
                $achievement->my_progress = 0;
                continue;
            }

            if ($progress->unlocked_at !== null) {
                $achievement->my_progress = 100;
                continue;
            }

            $achievement->my_progress = round($progress->points / $achievement->points * 100, 1);
        }

        return compact('group');
    }
}