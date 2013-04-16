#Breadcrumb

A package for breadcrumb management. [![Build Status](https://secure.travis-ci.org/noherczeg/breadcrumb.png?branch=develop)](https://travis-ci.org/noherczeg/breadcrumb)

##Features:

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
####General usage:
#####Preparing the Package
+ [Constructor explained](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#1-preparing-the-package)

#####Preparing data to work with
+ [Registering a batch of segments from variable input types](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#a-registering-a-batch-of-segments-from-variable-input-types)
+ [Registering Segments piece by piece](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#b-registering-segments-piece-by-piece)
+ [Adding a segment which points to the base url](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#c-adding-a-segment-which-points-to-the-base-url)
+ [Removing Segments from the list](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#d-removing-segments-from-the-list)

#####Building breadcrumbs
+ [Building breadcrumbs](https://github.com/noherczeg/breadcrumb/blob/master/docs/usage_examples.md#3-building-breadcrumbs)

####Laravel 3 specific, detailed:

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