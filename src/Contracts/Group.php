<?php

namespace tizis\achievements\Contracts;

interface Group
{
    public function namespace(): \Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function levels(): int;

    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany;
}
