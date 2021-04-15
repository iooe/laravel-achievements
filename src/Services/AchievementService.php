<?php

namespace tizis\achievements\Services;

use Illuminate\Database\Eloquent\Model;
use tizis\achievements\Contracts\Achievement;

class AchievementService
{
    protected Model $user;

    public function __construct(Model $user)
    {
        $this->user = $user;
    }

    public function setProgress(Achievement $achievement, int $points)
    {
        $this->user->achievements()->syncWithoutDetaching([$achievement->id => ['points' => $points]]);

        if ($points >= $achievement->points) {
            $this->unlock($achievement);
            return;
        }

        if ($achievement->points > $points) {
            $this->lock($achievement);
        }

        if ($points === 0) {
            $this->delete($achievement);
        }
    }

    public function addProgress(Achievement $achievement, int $points)
    {
        if ($this->user->hasAchievement($achievement) === null) {
            $this->push($achievement, $points);

            return;
        }

        $achievementDetail = config('achievements.models.progress')::where('id', $this->user->hasAchievement($achievement)->progress->id)->first();

        if ($achievementDetail->isUnlocked()) {
            return;
        }

        $newPoints = $achievementDetail->points + $points;

        $this->user->achievements()->syncWithoutDetaching([$achievement->id => ['points' => $newPoints]]);

        if ($newPoints >= $achievement->points) {
            $achievementDetail->unlock();
        }
    }

    public function delete(Achievement $achievement)
    {
        $this->user->achievements()->detach($achievement);
    }

    public function lock(Achievement $achievement)
    {
        $this->user->achievements()->syncWithoutDetaching([$achievement->id => ['unlocked_at' => null]]);
    }

    public function unlock(Achievement $achievement)
    {
        $achievementDetail = config('achievements.models.progress')::where('id', $this->user->hasAchievement($achievement)->progress->id)->first();

        if (!$achievementDetail->isUnlocked()) {
            $achievementDetail->unlock();
        }
    }

    protected function push(Achievement $achievement, ?int $points)
    {
        $this->user->achievements()->attach($achievement, ['points' => $points]);

        if ($points >= $achievement->points) {
            $this->unlock($achievement);
        }
    }
}
