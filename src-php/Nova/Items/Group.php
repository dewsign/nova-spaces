<?php

namespace Dewsign\NovaSpaces\Nova\Items;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Group extends SpaceItem
{
    public static $model = 'Dewsign\NovaSpaces\Models\Group';

    public static function label()
    {
        return __('Group');
    }

    public function fields(Request $request)
    {
        return [];
    }
}
