<?php

namespace Dewsign\NovaSpaces\Models;

use Illuminate\Support\Facades\View;

class Markdown extends SpaceItem
{
    protected $table = 'spaces_items_markdowns';

    public static $repeaterBlockViewTemplate = 'nova-spaces::markdown';
}
