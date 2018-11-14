<?php

namespace Dewsign\NovaSpaces\Models;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Maxfactor\Support\Model\Traits\HasSortOrder;
use Maxfactor\Support\Model\Traits\CanBeFeatured;
use Maxfactor\Support\Model\Traits\HasActiveState;
use Dewsign\NovaRepeaterBlocks\Traits\HasRepeaterBlocks;

class Space extends Model implements Sortable
{
    use HasSortOrder;
    use CanBeFeatured;
    use HasActiveState;
    use HasRepeaterBlocks;

    public function spaces()
    {
        return $this->morphMany(Space::class, 'spaceable')->with('type');
    }

    public function spaceable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            optional($model->type)->delete();
        });
    }

    public function getActionAttribute()
    {
        if (!method_exists($this->type, 'resolveAction')) {
            return null;
        }

        return $this->type->resolveAction();
    }

    public function getLabelAttribute()
    {
        if (!method_exists($this->type, 'resolveLabel')) {
            return $this->title;
        }

        return $this->type->resolveLabel($this);
    }

    public function getViewAttribute()
    {
        if (!method_exists($this->type, 'resolveView')) {
            return null;
        }

        return $this->type->resolveView($this);
    }

    public static function globals($overwrite = [])
    {
        $spaces = self::whereIn('id', array_wrap($overwrite))->get();

        return $spaces->count() ? $spaces : self::featured()->active()->get();
    }
}
