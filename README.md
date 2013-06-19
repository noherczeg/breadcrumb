#Breadcrumb

A package for breadcrumb management. [![Build Status](https://secure.travis-ci.org/noherczeg/breadcrumb.png?branch=develop)](https://travis-ci.org/noherczeg/breadcrumb)

##Features:

+ Framework agnostic
+ Can use language files, or arrays to translate breadcrumb segments
+ Segments can be made by using a URI string, JSON array, or PHP array
+ Can create HTML, Twitter Bootstrap , Zurb Foundation breadcrumbs
+ Segments can be ignored/removed/appended in any number from both sides
+ Translations can be overwritten
+ User made builders are supported
+ Custom separators
+ 4 different casing options: null _(keeps source intact)_, lower, upper, title
+ Supports attribute injection when building HTML breadcrumbs
+ Can skip linking to last element
+ Method chaining for almost everything
+ Has a Service Provider for Laravel 4

##Installation:

This package is based on composer, so if you have it installed you're pretty much halfway done already.

### Composer:
Download and install composer from `http://getcomposer.org/download/`

Add this to your project root's `composer.json` file:
```
{
    "require": {
        "noherczeg/breadcrumb": "dev-master"
    }
}
```
If you're done, just run a `php composer install`, and the package is ready to be used!

## Laravel specific settings
Please keep in mind that this package is framework agnostic! You can use it by itself as well with any framework out there!

### Laravel 3 setup:
If you're using laravel 3 you will have to do the following to be able to use composer based packages:

- In your project dir's `paths.php` add the following line: `$paths['composer'] = 'vendor';`
- In your project dir's public/index.php: after the `require '../paths.php';` line add the following: `require path('composer').'autoload.php';`

### Registering package with Laravel 4 as a service:
Put the following in your `app/config/app.php` file under `providers` array:
```
'Noherczeg\Breadcrumb\BreadcrumbServiceProvider'
```

Adding the alias of the facade is done in the same file under `aliases`:
```
'Breadcrumb' => 'Noherczeg\Breadcrumb\Facades\Breadcrumb'
```

Overriding the default configurations is done in a published config file. You can create it by typing:
```
$ php artisan config:publish noherczeg/breadcrumb
```

##Usage examples:
###General usage:

####1) Preparing the Package:

+ `$base_url (String|required)`: The base url of your site
+ `$use_language (mixed)`: The language code (file's name from the Languages folder), or dictionary array which will be used for translation

```
// Add your base url as you wish, like in Laravel's case: URL::base() or 'http://localhost/breadcrumb/' or whatever
$bc = new \Noherczeg\Breadcrumb\Breadcrumb(URL::base());
```

Using non default language (`en`, or one which is set in your configuration):
```
$bc = new \Noherczeg\Breadcrumb\Breadcrumb('http://localhost/breadcrumb/', 'de');
```

Or using a custom translation array (since 2.0.2):
```
$dictionary = array('this' => 'to_this', 'what' => 'what not?');
$bc = new \Noherczeg\Breadcrumb\Breadcrumb('http://localhost/breadcrumb/', $dictionary);
```

####2) Registering a batch of segments from variable input types:

__Warning__:
+ As of build 2.0.0 this method no longer tries to set the first element as a root element! If you'd like to add one (which points to the base url of your site), scroll down a bit and you'll get an example how to do it.
+ All of the given segments will be translated if the translator has a match in the provided dictionary!

Supported sources: `Array`, `JSON Array`, `String`
```
// Using a PHP array to fill our list
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));

// or JSON array
$bc->from('["asds", "12323", "Qw-Er-Ty"]');

// or URI String
$bc->from('First-Segment/Second/3rdWhatever');
```

####3) Registering Segments piece by piece:
You can add segments one by one, and you can also tell Breadcrumb which side to put them in. This is useful when you are processing the data piece by piece and you don't necessary know the order of the final result at the start.

You can tell Breadcrumb if you don't want to translate a particular element, or if you want it to be a specific value

+ `$name (String|required)`: The segment/slug/URI piece it self (this will remain unchanged)
+ `$side (String)`: The side where to place the Segment (`left`/`right`, default is `right`)
+ `$base (Boolean)`: Is this element an element which points to the base URL of the site? (`true`/`false`, default is `false`)
+ `$translate (mixed)`: If true: It'll try to translate the element from the dictionary, if false: it'll ignore translation, if String, then it'll use the given value (true by default)

_We now use method chaining as well, but it's just an option :)_
```
/**
 * The order in which the Segments will be stored in the list is: first, second, third, fourth.
 */
$bc->append('third');
$bc->append('fourth', 'right');
$bc->append('second', 'left')->append('first', 'left', true);

```

####4) Adding a segment which points to the base url:
Since the latest build (2.0.0) a major logic problem has been fixed. Now from whatever source you've used to seed Breadcrumb doesn't automatically set the first element as a root element. This fixed the issue with the links! From now on if you want to add an element which points to the base url, you have to append one whenever you'd like to:
```
// this way you'll have a first element 'welcome' which will point to your base url
$bc->from('/action/parameter1/parameter2')->append('welcome', 'left', true);
```

####5) Removing Segments from the list:
You can remove segments one by one from the registered bunch.

+ `$pos (int|required)`: The index of the element which you want to remove
+ `$reindex (Boolean)`: Is a boolean value which may trigger a re-index on the remaining elements after removal (default is: `false`)

```
// we provide a base list of Segments before removal
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));

// remove the Segment on index: 2
$bc->remove(2);

// remove the Segment on index: 0, and re-index the list after
$bc->remove(0, true);

/**
 * Result will be (not an actual var_dump!)
 * 0 => 'second'
 * 1 => 'Fourth-Thingy'
 */
```

####6) Overwriting translation:
_Since: 2.0.2_

```
// this way you can tell the translator to translate the given value to "overwritten" no matter what
$bc->append(URI::segment(3), 'left', true, 'overwritten');
```

####7) Building breadcrumbs:
Creating breadcrumbs can be simple or complicated as well if you desire. The package also lets you define your own builders as well (examine the Builders folder if you want to).

+ `$format (String)`: The format of the created breadcrumbs (`bootstrap`, `foundation`, `html`)
+ `$casing (mixed)`: Sets the casing of the text in the breadcrumbs (`null`, `upper`, `lower`, `title`, default is `null`, which means no transformation is done)
+ `$last_not_link (Boolean)`: If set to false, then the last element will be a link as well, by default it's `true`
+ `$separator (String)`: The separator in between breadcrumbs (applied only to html and bootstrap stlye breadcrumbs, since there is no separator in Zurb Foundation!)
+ `$customizations (Array)`: An array of customizations can be set to elements where the keys are the properties and the values are the values (only works with html type breadcrumbs!)

#####First we start out by adding some elements to work with
```
// Sample list initialization
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));
```

#####a) Creating breadcrumbs with _default_ settings:
```
/**
 * This will build default format(html) breadcrumbs with the default separator( / )
 * without any casing modification or property injection, and the last element
 * won't be a link
 */
echo $bc->build();
```

#####b) Creating _Zurb Foundation_ style breadcrumbs:
```
/**
 * This will build Zurb Foundation styled breadcrumbs with all links upper cased, and
 * the last element will be a link too
 */
echo $bc->build('foundation', 'upper', false);
```

#####c) Creating _Bootstrap_ style breadcrumbs:
```
/**
 * This way you'll get Bootstrap styled breadcrumbs with unmodified casing, the last
 * element as link, and a "+" as separator
 */
echo $bc->build('bootstrap', null, false, '+');
```

#####d) Creating complex _HTML_ breadcrumbs:
```
/**
 * This will create HTML breadcrumbs which will have lower cased links, last
 * element won't be a link, default separator will be ` / `, and will inject properties
 * which are in the array.
 */
echo $bc->build('html', 'lower', true, null, array('style' => 'font-family: Arial; font-size: 11px;', 'class' => 'some_class'));

```

####8) Utility functions:
These functions exist to somewhat ease your work with this package.

+ List of registered Segments: `var_dump($bc->registered());`
+ Picking one element from the list: `var_dump($bc->segment(1));`
+ Total number of Segments in the list: `var_dump($bc->num_of_segments());`

###Laravel 3 specific, detailed:

+ [Using Breadcrumb with a base controller](https://github.com/noherczeg/breadcrumb/blob/master/docs/laravel3_guide.md#using-breadcrumb-with-a-base-controller)

##Changelog:

###Breadcrumb 2.0.2
+ Added ability to provide translation arrays (dictionaries) as second parameter to the constructor
+ Users now can overwrite or ignore translations while building breadcrumbs via the `append()` method

###Breadcrumb 2.0.1
+ The package can now publish configurations to Laravel 4 via artisan
+ Added Laravel 4 Service Provider

###Breadcrumb 2.0.0
+ The list of builders is now obtained automatically, no further need for maintaining a list manually
+ __ciritcal__: using `from()` now desn't automatically create the first element as root! If you want to always have a segment point to the root, you should append() one to the left side(second param: `'left'`) with the third parameter set to `true`!

_Example has been provided in the corresponding section of this document._

###Breadcrumb 2.0.0-alpha1
+ The bundle has been rewritten from scratch, now available as a composer package
+ Corrected application logic flaws _(e.g. base segment)_
+ Methods are now chainable
+ Added support for user made builders
+ Added support for URI string input
+ Now written with tests along

###Breadcrumb 1.2

+ Refactored the comments in the code.
+ Fixed bundle initialization bug where the bundle started with an auto translation.
+ Added support for proper slug handling.
+ Added get_raw_segments() for better debugging.
+ Added append(), so now any number of segments can be added to either side of the working array.
+ Added remove(), which can remove any number of segments from the working array, index handling provided.
+ Added slug_separator and word_separator options to config file.
+ Replaced inferior documentation with a new, better, more detailed one.

###Breadcrumb 1.1

+ Renamed methods for better reading.
+ Added Bootstrap output support.
+ Added external bundle translation file handling option.
+ Provided usage examples in the docs.
+ Added scan_bundles, output_format option to the config file.

###Breadcrumb 1.0
+ Bundle start.

##Dev's note:
If you notice an error / logic failure, or know something that is missing, please let me know via pull requests or issues. I'm tracking this repo, and will do it forever, so you can reach me :)