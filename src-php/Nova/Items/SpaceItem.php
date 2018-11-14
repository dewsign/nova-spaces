<?php

namespace Dewsign\NovaSpaces\Nova\Items;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Dewsign\NovaRepeaterBlocks\Traits\IsRepeaterBlockResource;

class SpaceItem extends Resource
{
    use IsRepeaterBlockResource;

    public static function label()
    {
        return __('Space Item');
    }

    public function fields(Request $request)
    {
        return [];
    }
}
