<?php

namespace Dewsign\NovaSpaces\Nova\Items;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Markdown as MarkdownField;

class Markdown extends SpaceItem
{
    public static $model = 'Dewsign\NovaSpaces\Models\Markdown';

    public static function label()
    {
        return __('Markdown');
    }

    public function fields(Request $request)
    {
        return [
            MarkdownField::make('Content')->rules('required'),
        ];
    }
}
