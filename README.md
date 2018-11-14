# Spaces for Laravel Nova

Create "Spaces" to display throughout your website which can be populated with custom blocks.

## Installation

`composer require dewsign/nova-spaces`

`php artisan migrate`

## Usage

To get started, you will need to create your first Space area. E.g. Sidebar or Upsells. You are free to structure the files as you wish or you can use the conventions from the examples if you prefer.

We simply use the default Nova folder to register our new space as it will load automatically.

```php
// app/Nova/SidebarSpace.php

namespace App\Nova;

use Dewsign\NovaSpaces\Nova\Space;

class SidebarSpace extends Space
{
    public static $zone = 'sidebar';

    public static function label()
    {
        return __('Sidebar Items');
    }
}
```

The `$zone` is used to differentiate the various space areas in the database and code and avoids requiring new tables for each new space area.

Your new space zone should now be available within Nova, with the default custom link item as the only option.

## Outputting the space (Blade)

We don't currently make any assumptions about how you wish to render the space. Some helpers surrounding common usage are planned for the future though. For now please access the `Dewsign\NovaSpaces\Models\Space` model as you sould any other Eloquent model to retrieve the space items you require.

Here is a basic inline blade example.

```php
@foreach(Space::active()->whereZone('sidebar')->get() as $spaceItem)
    {!! $spaceItem->view !!}
    {!! or !!}
    <a href="{{ $spaceItem->action }}">{{ $spaceItem->label }}</a>
@endforeach
```

You can access any sub-items through the `spaces` relationship.

```php
@foreach($spaceItem->spaces as $item)
    {!! $item->view !!}
@endforeach
```

## Extending

You can create your own space item types by creating a couple of new files and loading them in. In short, you will need:

* An Eloquent Model, complete with migration / database
* A Nova resource to manage the content
* A blade view to render the item

```php
// app/Space/Models/Section.php

use Dewsign\NovaSpaces\Models\SpaceItem;

class Section extends SpaceItem
{
    public static $repeaterBlockViewTemplate = 'space.section';

    public function resolveAction()
    {
        return $this->link_url;
    }

    public function resolveLabel($model = null)
    {
        return $model->title ?? $this->heading;
    }
}
```

```php
// database/migrations/your_migration.php

Schema::create('space_sections', function (Blueprint $table) {
    $table->increments('id');
    $table->string('heading')->nullable();
    $table->text('content')->nullable();
    $table->string('link_url')->nullable();
    $table->string('link_title')->nullable();
    $table->timestamps();
});
```

```php
// app/Space/Nova/Section.php

...
use Dewsign\NovaSpaces\Nova\Items\SpaceItem;

class Section extends SpaceItem
{
    public static $model = App\Space\Models\Section::class;

    public static $title = 'heading';

    public static $search = [
        'heading',
        'content',
        'link_url',
    ];

    public static function label()
    {
        return __('Section');
    }

    public function fields(Request $request)
    {
        return [
            Text::make('Heading'),
            Markdown::make('Content'),
            Text::make('Link Url'),
            Text::make('Link Title'),
        ];
    }
}
```

Finally, load the new space item through the `novaspaces` config

```php
return [
    "repeaters" => [
        ...
        \App\Space\Nova\Section::class,
    ],
];
```
