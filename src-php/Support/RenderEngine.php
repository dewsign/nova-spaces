<?php

namespace Dewsign\NovaSpaces\Support;

class RenderEngine
{
    /**
     * Renders a collection of spaces with their entries
     *
     * @param $spaces
     * @return string
     */
    public static function renderSpaces($spaces)
    {
        // dd($spaces);
        return optional($spaces)->filter(function ($space) {
            return $space->type !== null;
        })->map(function ($space) {
            $spaceType = new \ReflectionClass($space->type);
            $spaceKey = str_replace('\\', '.', $spaceType->name);
            $spaceShortKey = $spaceType->getShortName();
            $spaceContent = $space->type;
            $space = $space;

            return view()->first([
                $spaceType->hasMethod('getBlockViewTemplate')
                    ? $spaceType->getMethod('getBlockViewTemplate')->invoke(null)
                    : null,
                "spaces.{$spaceKey}",
                "nova-spaces::{$spaceKey}",
                'spaces.default',
                'nova-spaces::default'
                ])->with([
                    'space' => $space,
                    'spaceKey' => $spaceKey,
                    'spaceShortKey' => $spaceShortKey,
                    'spaceContent' => $spaceContent,
                ])->with($spaceContent->toArray())
                ->render();
        })->implode('');
    }
}
