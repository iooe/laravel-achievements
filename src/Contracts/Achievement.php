<?php

namespace tizis\achievements\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Achievement
{
    public function completedProgress();

    public function progress(): \Illuminate\Database\Eloquent\Relations\HasMany;

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function scopeSingleOnly(Builder $query): Builder;

    public function isSingle(): bool;
}
