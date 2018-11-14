<?php

namespace Dewsign\NovaSpaces\Models;

use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Dewsign\NovaRepeaterBlocks\Traits\IsRepeaterBlock;

class SpaceItem extends Model
{
    use IsRepeaterBlock;

    public static $repeaterBlockViewTemplate = 'nova-spaces::default';

    public function type()
    {
        return $this->morphTo();
    }

    public function resolveView($model)
    {
        return View::make(static::$repeaterBlockViewTemplate)
            ->with('item', $this)
            ->with('model', $model)
            ->render();
    }
}
