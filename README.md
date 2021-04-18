#Readme coming soon

#Install


composer require tizis/achievements

##Publish Migrations & migrate (optional)


php artisan vendor:publish --provider="tizis\achievements\Providers\ServiceProvider" --tag=migrations
php artisan migrate


##Publish Config & configure (optional)


php artisan vendor:publish --provider="tizis\achievements\Providers\ServiceProvider" --tag=config 


#Models


```php
use tizis\achievements\Contracts\HasAchievements;
use tizis\achievements\Traits\InteractsWithAchievements;

class User extends Authenticatable implements HasAchievements {
	use InteractsWithAchievements;
}
```   


#Examples

```php

  		$user = User::where('id', 1)->first();

        $achievement = Achievement::where('hash', 'first-generation')->first();

        (new AchievementService($user))->addProgress($achievement, 1);
      
``` 

```php
   $user = User::where('id', 1)->first();

   $service = new GroupAchievementService($user);
   
   $count = 10;
   
   $achievementGroup = Group::where('id', 1)->first();
   
   $service->addProgress($achievementGroup, $count);
     ```  ```   