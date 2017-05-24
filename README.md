# Eloquent-Tags

A tagging package for Laravel Eloquent models.

## Requirements

This package requires Laravel 5.3 or higher and PHP 7.0 or higher.

## Installation

1. Install the `shamilchoudhury/eloquent-tags` package via composer:

    ```shell
    $ composer require shamilchoudhury/eloquent-tags
    ```
    
2. Add the service provider to `providers` array in `config/app.php`:

    ```php
    'providers' => [
        ...
        \Shamil\Tags\Providers\TagsServiceProvider::class,
    ],

    ```

3. Use artisan to run the migration to create the required tags tables.

    ```sh
    php artisan migrate
    ```


## Setup your Models

To make a model taggable, use the Taggable trait:

```php
use Shamil\Tags\Taggable;

class Lesson extends Model
{
    use Taggable;
}
```

Done. Now your model is "taggable"!


## Usage

Tag your models with the `tag()` method:

```php
// Pass in an array
$lesson->tag(['snow', 'linen']);

// or you can pass in a model
$lesson->tag(\Shamil\Tags\Models\Tag::where('name', 'snow')->first());

// or a collection
$lesson->tag(\Shamil\Tags\Models\Tag::whereIn('name', ['snow', 'linen'])->get());

```

The `tag()` method is additive, so you can easily add new tags to the existing ones:

```php
$lesson->tag(['linen']); // lesson has one tag 'linen'

$lesson->tag(['snow', 'linen']); // lesson now has two tags: 'linen' and 'snow'

$lesson->tag(['navy']); // lesson has three tags

```

Tag names are normalized to avoid duplicate tags:

```php
$lesson->tag(['snow', 'SNOW', 'sNoW']); // lesson will be tagged with 'snow' only once

```

You can easily grab tags associated with a model using their relationship:

```php
$lesson = Lesson::find(1);

foreach($lesson->tags as $tag) {
    echo $tag->name . ' ' ;
    // or do other stuff
}

```

Since this is a direct relationship, you can easily order tags by their count:

```php
$lesson = Lesson::find(1);

foreach($lesson->tags()->orderBy('count', 'desc')->get() as $tag) {
    echo $tag->name . ' ' ; // this will echo tags in decreasing order of their count
}

```

Convert all tags associated with a model to an array:

```php
$lesson = Lesson::find(1);

echo implode(' &bull; ', $lesson->tags->pluck('name')->toArray()); // Seperate tags by bullet points

```

You can grab a model with specific tags using query scopes:

```php
// withAnyTag()
$lesson = Lesson::withAnyTag(['linen', 'snow', 'navy']);
dd($lesson->get()); // take any lesson that is tagged with any of the provided tags

//withAllTags()
$lesson = Lesson::withAllTags(['yellowgreen', 'navy']);
dd($lesson->get()); // only take lessons that are tagged with all of the provided tags

//withoutTags()
$lesson = Lesson::withoutTags(['snow', 'linen']);
dd($lesson->get()); // only take lessons that are not tagged with all of the provided tags

```

You can delete all current tags and add new tags with `retag()` :

```php
$lesson = Lesson::find(1);

$lesson->retag(['darkorange']); 
// all existing tags are removed and model is tagged with the tags provided
// in other words, model is first detagged and then tagged with the new tags

```

You can remove tags with `untag()` :

```php
$lesson->tag(['lightcoral', 'yellowgreen', 'navy']);

$lesson->untag(['lightcoral']);
// $lesson is now tagged with "yellowgreen" and "navy"

```

Simply use `untag()`  to remove all tags:

```php
$lesson = Lesson::find(1);

$lesson->untag(); // all tags are removed
dd($lesson->tags); // $lesson now has an empty collection of tags

```

You can grab tags based on their count using the orderable scopes:

```php
$tags = \Shamil\Tags\Models\Tag::usedGte(2); // tags with count greater than or equal to 2
dd($tags->get()); // all tags that have been used twice or more

// Similarly you can use other scopes

$tags = \Shamil\Tags\Models\Tag::usedGt(2); // tags with count greater than 2

$tags = \Shamil\Tags\Models\Tag::usedLte(2); // tags with count less than or equal to 2

$tags = \Shamil\Tags\Models\Tag::usedLt(2); // tags with count less than 2


```

## License

Eloquent-tags is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
