<?php

namespace tizis\achievements;

use tizis\achievements\Contracts\Achievementable;

class AchievementHelper
{
    public static function count(Achievementable $user)
    {
        return $user->achievements()
            ->wherePivot('unlocked_at', '!=', null)
            ->count();
    }

    public static function lastUnlockedAchievements(Achievementable $user, int $take = 10)
    {
        return $user->achievements()
            ->wherePivot('unlocked_at', '!=', null)
            ->orderBy('achievements_progress.unlocked_at', 'desc')
            ->take($take)
            ->get();
    }

    public static function lastUnlockedAchievement(Achievementable $user)
    {
        return $user->achievements()
            ->wherePivot('unlocked_at', '!=', null)
            ->orderBy('achievements_progress.unlocked_at', 'desc')
            ->first();
    }

    public static function getUnlockedUniqueAchievementsOfUser(Achievementable $user)
    {
        $collection = collect();

        $unlockedAchievements = $user->achievements()
            ->wherePivot('unlocked_at', '!=', null)
            ->singleOnly()
            ->get();

        $usedGroups = [];

        foreach ($unlockedAchievements as $achievement) {

            if ($achievement->isSingle()) {
                $collection->push($achievement);
                continue;
            }

            if (in_array($achievement->group_id, $usedGroups)) {
                continue;
            }

            $group = $user->getAchievementsOfGroup($achievement->group);
            $group->title = $achievement->group->title;
            $group->count = $achievement->group->achievements()->count();
            // ->wherePivot('unlocked_at', '!=', null);
            $collection->push($group);
            $usedGroups[] = $achievement->group_id;
        }

        return $collection;
    }

    public static function unlockedAchievementsOnlyGroups(Achievementable $user)
    {
        $collection = collect();

        $unlockedAchievements = $user->achievements()
            ->wherePivot('unlocked_at', '!=', null)
            ->with('group')
            ->get();

        $usedGroups = [];
        foreach ($unlockedAchievements as $achievement) {


            if ($achievement->isSingle()) {
                /*                $uniqueAchievements->push($achievement);*/
                continue;
            }

            if (in_array($achievement->group_id, $usedGroups)) {
                continue;
            }

            $group = $user->getAchievementsOfGroup($achievement->group);
            $group->title = $achievement->group->title;
            $group->id = $achievement->group->id;
            $group->meta = $achievement->group->meta;
            $group->namespace_id = $achievement->group->namespace_id;
            $group->count = $achievement->group->achievements()->count();


            $collection->push($group);
            $usedGroups[] = $achievement->group_id;
        }


        $namespaces = collect(config('achievements.models.group_namespace')::get()->toArray());
        $namespaces = $namespaces->map(function ($namespace, $key) use ($collection) {

            $namespace['groups'] = $collection->where('namespace_id', $namespace['id'])->all();

            return (object)$namespace;
        });

        return $namespaces;
    }
}