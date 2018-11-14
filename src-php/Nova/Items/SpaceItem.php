<?php

namespace Dewsign\NovaSpaces\Nova\Items;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Epartment\NovaDependencyContainer\HasDependencies;
use Dewsign\NovaRepeaterBlocks\Traits\HasRepeaterBlocks;
use Dewsign\NovaRepeaterBlocks\Traits\IsRepeaterBlockResource;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class SpaceItem extends Resource
{
    use HasDependencies;
    use HasRepeaterBlocks;
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
