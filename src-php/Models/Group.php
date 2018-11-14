<?php

namespace Dewsign\NovaSpaces\Models;

use Illuminate\Support\Facades\View;

class Group extends SpaceItem
{
    protected $table = 'spaces_items_groups';

    public static $repeaterBlockViewTemplate = 'nova-spaces::group';
}
