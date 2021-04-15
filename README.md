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
use tizis\achievements\Contracts\Achievementable as AchievementableContract;
use tizis\achievements\Traits\Achievementable;

class User extends Authenticatable implements AchievementableContract {
	use Achievementable;
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