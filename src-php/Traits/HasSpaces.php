<?php

namespace Dewsign\NovaSpaces\Traits;

use Dewsign\NovaSpaces\Models\Space;

trait HasSpaces
{
    public function spaces()
    {
        return $this->morphMany(Space::class, 'spaceable')->with('type');
    }
}
