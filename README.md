```
    "php": "^7.1|^8.0",
    "illuminate/support": "^8.0",
```


## Install

`composer require tizis/achievements`

### Publish Migrations & Migrate

```
php artisan vendor:publish --provider="tizis\achievements\Providers\ServiceProvider" --tag=migrations`
php artisan migrate
```

### Publish Config & configure (optional)

`php artisan vendor:publish --provider="tizis\achievements\Providers\ServiceProvider" --tag=config `

## Examples

```php
use tizis\achievements\Contracts\HasAchievements;
use tizis\achievements\Traits\InteractsWithAchievements;

class User extends Authenticatable implements HasAchievements {
	use InteractsWithAchievements;
}
```   

### Example #1: Basic
```php
use App\Models\User;
use tizis\achievements\Entity\Achievement;
use tizis\achievements\Entity\Group;
use tizis\achievements\Entity\GroupNamespace;
use tizis\achievements\Services\GroupAchievementService;
...
$createdGroup = new Group([
    'title' => 'Running',
    'description' => 'Sport is cool',
    'hash' => 'sport_activities_running',
    'meta' => 'keywords or something like'
]);

$createdGroupNamespace = GroupNamespace::create(['title' => 'Activities']);
$createdGroupNamespace->groups()->save($createdGroup);

$createdGroup->achievements()->saveMany([
    new Achievement([
        'title' => 'Running: Beginner',
        'level' => 1,
        'description' => 'optional',
        'points' => '10', //to next level
        'value' => '1000' // it's just abstract achievement 'value'
    ]),
    new Achievement([
        'title' => 'Running: Pro',
        'level' => 2,
        'description' => 'optional',
        'points' => '100', //to next level
        'value' => '1000000' // it's just abstract achievement 'value'
    ])
]);

$user = User::where('id', 1)->first();

$service = new GroupAchievementService($user);
$service->addProgress($createdGroup, 20);

 ```   
 
  
### Example #2: Basic 2
```php
use App\Models\User;
use tizis\achievements\Entity\Group;
use tizis\achievements\Services\GroupAchievementService;
...
$group = Group::where('hash', 'sport_activities_running')->firstOrFail();
$user = User::where('id', 1)->first();

$service = new GroupAchievementService($user);
$service->removeProgress($group, 20);

 ```   
 
### Example #3: Achievement without group
```php
use App\Models\User;
use tizis\achievements\Entity\Achievement;
use tizis\achievements\Services\AchievementService;
...
$createdSingleAchievement = Achievement::create([
    'title' => 'Running: Beginner',
    'level' => 1,
    'description' => 'optional',
    'points' => '100', //to next level
    'value' => '1000' // it's just abstract achievement 'value'
]);

$user = User::where('id', 1)->first();

$service = new AchievementService($user);
$service->addProgress($createdSingleAchievement, '100');

 ```   
 
 ### Example #4: Helper
```php
use App\Models\User;
use tizis\achievements\AchievementHelper;
...
$user = User::where('id', 1)->first();

AchievementHelper::count($user); // return count of user's unlocked achievements
AchievementHelper::lastUnlockedAchievements($user, 100); // return last 100 unlocked achievements
AchievementHelper::lastUnlockedAchievement($user); // return last unlocked achievement
AchievementHelper::getUnlockedUniqueAchievementsOfUser($user); // return unlocked unique achievements (without group)

 ```   
