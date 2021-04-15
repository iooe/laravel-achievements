<?php

namespace tizis\achievements\Contracts;

interface Progress
{
    public function achievement(): \Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function scopeUnlocked($query);

    public function isUnlocked(): bool;

    public function unlock(): void;
}
