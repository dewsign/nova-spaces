<?php

namespace Dewsign\NovaSpaces\Nova;

use Laravel\Nova\Resource;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\MorphMany;
use Silvanite\NovaFieldHidden\Hidden;
use Dewsign\NovaFieldSortable\IsSorted;
use Dewsign\NovaFieldSortable\Sortable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Dewsign\NovaRepeaterBlocks\Fields\Repeater;
use Dewsign\NovaRepeaterBlocks\Fields\Polymorphic;

class Space extends Repeater
{
    use IsSorted;

    public static $title = 'label';

    public static $displayInNavigation = true;

    public static $zone = 'global';

    public static $model = 'Dewsign\NovaSpaces\Models\Space';

    public static $group = 'Spaces';

    public static $search = [
        'title',
        'zone',
    ];

    public static function label()
    {
        return __('Spaces');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Sortable::make('Sort', 'id'),
            ID::make(),
            Boolean::make('Active')->rules('required', 'boolean'),
            $this->globalToggle($request),
            MorphTo::make('Parent', 'spaceable')->types(Arr::wrap(static::class))->onlyOnDetail(),
            Text::make('Title')->rules('nullable', 'max:254')->hideFromIndex(),
            Text::make('Label', function () {
                return $this->label;
            }),
            Hidden::make('Zone')->value(static::$zone),
            Polymorphic::make('Type')->types($request, $this->types($request)),
        ];
    }

    private function globalToggle($request)
    {
        return $this->isRootSpace($request) ? $this->merge([
            Boolean::make('Global', 'featured')->rules('required', 'boolean'),
        ]) : $this->merge([]);
    }

    private function isRootSpace($request)
    {
        if ($resource = $request->get('viaResource')) {
            return $resource === '';
        };

        parse_str(parse_url($request->headers->get('referer'), PHP_URL_QUERY), $params);

        if ($resource = Arr::get($params, 'viaResource')) {
            return $resource === '';
        };

        if ($resourceId = $request->route('viaResource')) {
            return $resource === '';
        };

        return true;
    }

    // What type of repeater blocks should be made available
    public function types(Request $request)
    {
        return config('novaspaces.repeaters');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        parent::indexQuery($request, $query);

        if (static::getResourceIdFromRequest($request)) {
            return $query;
        }

        return $query->where(function ($query) {
            return $query->whereNull('spaceable_type')->whereZone(static::$zone);
        });
    }

    /**
     * Get the resource ID of the current repeater item
     *
     * @param Request $request
     * @return mixed
     */
    protected static function getResourceIdFromRequest(Request $request)
    {
        if ($resourceId = $request->get('viaResourceId')) {
            return $resourceId;
        };

        parse_str(parse_url($request->server->get('HTTP_REFERER'), PHP_URL_QUERY), $params);

        if ($resourceId = Arr::get($params, 'viaResourceId')) {
            return $resourceId;
        };

        if ($resourceId = $request->route('resourceId')) {
            return $resourceId;
        };

        return null;
    }
}
