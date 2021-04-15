<?php

namespace tizis\achievements\Entity;

use App\Entity\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use tizis\achievements\Events\AchievementUnlocked;

class Progress extends Model implements \tizis\achievements\Contracts\Progress
{
    protected $table = 'achievements_progress';

    protected $dates = [
        'created_at',
        'updated_at',
        'unlocked_at',
    ];

    protected $fillable = [
        'user_id', 'achievement_id', 'points'
    ];


    public function achievement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('achievements.models.achievement'));
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('achievements.models.user'));
    }

    public function scopeUnlocked($query)
    {
        return $query->whereNotNull('unlocked_at');
    }

    public function isUnlocked(): bool
    {
        return $this->attributes['unlocked_at'] !== null;
    }

    public function unlock(): void
    {
        $this->attributes['unlocked_at'] = Carbon::now();
        $this->save();

        event(new AchievementUnlocked($this));
    }
}
