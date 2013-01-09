#Breadcrumb

A package for breadcrumb management. [![Build Status](https://secure.travis-ci.org/noherczeg/breadcrumb.png?branch=develop)](https://travis-ci.org/noherczeg/breadcrumb)

##Features:

+ Uses primarily language files to translate breadcrumb segments
+ Segments can be made by using a URI string, JSON array, or PHP array
+ Can create HTML, Twitter Bootstrap , Zurb Foundation breadcrumbs
+ Segments can be ignored/removed/appended in any number from both sides
+ User made builders are supported
+ Custom separators
+ 4 different casing options: null _(keeps source intact)_, lower, upper, title
+ supports attribute injection when building HTML breadcrumbs
+ can skip linking to last element
+ method chaining for almost everything

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

### Laravel 3 setup:
If you're using laravel 3 you will have to do the following to be able to use composer based packages:

- In your project dir's `paths.php` add the following line: `$paths['composer'] = 'vendor';`
- In your project dir's public/index.php: after the `require '../paths.php';` line add the following: `require path('composer').'autoload.php';`

##Usage examples:
####General usage
1. Preparing the Package

+ [Constructor explained]()

2. Preparing data to work with

+ [Registering a batch of segments from variable input types]()
+ [Registering Segments piece by piece]()
+ [Adding a segment which points to the base url]()
+ [Removing Segments from the list]()
+ [Building breadcrumbs]()

####Laravel 3 specific, detailed

+ [Using Breadcrumb a base controller]()

##Changelog:

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