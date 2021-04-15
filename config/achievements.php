<?php

return [
    'models' => [
        'achievement' => \tizis\achievements\Entity\Achievement::class,
        'progress' => \tizis\achievements\Entity\Progress::class,
        'group' => \tizis\achievements\Entity\Group::class,
        'group_namespace' => \tizis\achievements\Entity\GroupNamespace::class,
        'user' => \App\Models\User::class,
    ],
];