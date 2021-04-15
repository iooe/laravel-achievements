<?php

namespace tizis\achievements\Contracts;

interface GroupNamespace
{
    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany;

    public function groups(): \Illuminate\Database\Eloquent\Relations\HasMany;
}
