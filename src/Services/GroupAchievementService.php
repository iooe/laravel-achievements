<?php

namespace tizis\achievements\Services;

use Illuminate\Database\Eloquent\Model;
use tizis\achievements\Contracts\Group;

class GroupAchievementService
{
    protected $user;

    public function __construct(Model $user)
    {
        $this->user = $user;
    }

    public function removeProgress(Group $group, int $points)
    {
        $singleAchievementService = new AchievementService($this->user);

        $userAchievements = $this->user->getAchievementsOfGroup($group);

        foreach ($userAchievements->reverse() as $achievement) {

            /**
             * Если кол-во поинтов у прогресса больше или равно поинтам на удаление.
             */
            if ($points - $achievement->progress->points <= 0) {

                $singleAchievementService->setProgress($achievement, $achievement->progress->points - $points);

                break;
            }

            $points = $points - $achievement->progress->points;

            $singleAchievementService->delete($achievement);
        }
    }

    public function addProgress(Group $group, int $points)
    {
        $singleAchievementService = new AchievementService($this->user);
        $achievementsList = $group->achievements;
        $userAchievements = $this->user->getAchievementsOfGroup($group);
        $minLevel = 0;

        if ($userAchievements->count() > 0) {

            $lastUserUnlockedAchievement = $userAchievements->last();
            $lastUserUnlockedAchievement->progress = config('achievements.models.progress')::where('id', $lastUserUnlockedAchievement->progress->id)->first();

            $minLevel = $lastUserUnlockedAchievement->level;

            $lastUnlockedAchievementIsLastAchievementInGroup = $this->twoAchievementsAreEqual($lastUserUnlockedAchievement, $achievementsList->last());

            /**
             * Если больше нет не разблокированных ачивок
             */
            if ($lastUserUnlockedAchievement->progress->isUnlocked() && $lastUnlockedAchievementIsLastAchievementInGroup) {
                return;
            }

            /**
             * Если последняя пользовательская ачивка разблокирована, а прогресс следующей еще не начат
             */
            if ($lastUserUnlockedAchievement->progress->isUnlocked() && !$lastUnlockedAchievementIsLastAchievementInGroup) {

                $minLevel = $lastUserUnlockedAchievement->level + 1;
            }
        }

        foreach ($achievementsList->where('level', '>=', $minLevel) as $achievement) {
            $progress = $this->user->hasAchievement($achievement);

            if ($progress) {
                $requiredPoints = $achievement->points - $progress->progress->points;
            } else {
                $requiredPoints = $achievement->points;
            }

            /**
             * Если кол-во требуемых очков больше добавляемых
             */
            if ($points - $requiredPoints <= 0) {
                $singleAchievementService->addProgress($achievement, $points);
                break;
            }

            $singleAchievementService->setProgress($achievement, $requiredPoints);

            $points = $points - $requiredPoints;
        }
    }

    private function twoAchievementsAreEqual($achievementOne, $achievementTwo): bool
    {
        return $achievementOne->id === $achievementTwo->id;
    }
}
