<?php

namespace tizis\achievements\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use tizis\achievements\Contracts\Progress;

class AchievementUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Progress $progress;

    public function __construct(Progress $progress)
    {
        $this->progress = $progress;
    }
}
