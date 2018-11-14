<?php
namespace Dewsign\NovaSpaces\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool as NovaTool;

class NovaSpacesTool extends NovaTool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::resources([]);
    }
}
