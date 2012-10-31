# Breadcrumb

Breadcrumb is a simple bundle made for the [Laravel framework](http://laravel.com/). It's goal is to lend a helping hand with the creation and management of breadcrumbs. If you have any issues with it, or have suggestions/questions, just contact me on github, or post an issue/feature request.

## Features

* Uses primarily language files to translate breadcrumb segments
* Can provide a 2 layered translation method*
* Segments can be URI segments or custom arrays
* Can dump the results in either PHP arrays, or JSON arrays
* Can produce full html breadcrumbs which can be formatted, let it be either html attributes or casing options
* Can create Twitter Bootstrap breadcrumbs
* Segments can be ignored/removed/appended in any number from both sides

\* 2 layered because (if configured so) it can get the translations from: language folders in bundles named as controllers, or this bundle it self. 

## Changelog

### Breadcrumb 1.2
- Refactored the comments in the code.
- Fixed bundle initialization bug where the bundle started with an auto translation.
- Added support for proper slug handling.
- Added Zurb Foundation output generator.
- Added `get_raw_segments()` for better debugging.
- Added `append()`, so now any number of segments can be added to either side of the working array.
- Added `remove()`, which can remove any number of segments from the working array, index handling provided.
- Added `slug_separator` and `word_separator` options to config file.
- Replaced inferior documentation with a new, better, more detailed one.

### Breadcrumb 1.1
- Renamed methods for better reading.
- Added Bootstrap output support.
- Added external bundle translation file handling option.
- Provided usage examples in the docs.
- Added `scan_bundles`, `output_format` option to the config file.

### Breadcrumb 1.0
- Bundle start.

##Quickstart

* Clone Breadcrumb into *APPPATH/bundles/*
  * ```git clone https://github.com/noherczeg/breadcrumb.git breadcrumb```
* Edit *APPPATH/application/bundles.php*

```php

<?php
// APPPATH/application/bundles.php
return array(
  'breadcrumb' => array('auto' => true),
);
```
* The bundle uses the *APPPATH/application/config/application.php* file to determine which language profile to load from the bundle's (external bundles) language folder
* No migration needed

## Usage examples:

### A) Basic functions:

#### Generating breadcrumbs from current URI:
With an URL like: `http://localhost/laravel-fork/public/admin/cron-jobs`, calling `echo Breadcrumb::make();` will produce this:
```php
<a href="http://localhost/laravel-fork/admin/">administration</a> / cron jobs
```

Since no parameters where given, it generated the links, etc.. according to the default settings

#### Dumping current working array:
Using an URL like: `http://localhost/laravel-fork/public/test-case/12`, calling `var_dump(get_raw_segments());` returns:
```php
array
  0 => string 'test-case' (length=9)
  1 => string '12' (length=2)
```

#### Dumping a translated array from the URI:
With an URL like: `http://localhost/laravel-fork/public/browser/firefox`, calling `var_dump(Breadcrumb::dump());` will return something like this:
```php
array
  0 => string 'browser' (length=7)
  1 => string 'a great browser' (length=15)
```
Doing the same, but with JSON array as result: `var_dump(Breadcrumb::dump('json'));` returns:

```php
["browser","a great browser"]
```
#### Generating twitter bootstrap breadcrumbs from the current URI:
Calling `echo Breadcrumb::make('bootstrap');` on: `http://localhost/laravel-fork/public/browser/admin/whatever`,  will produce:
```php
<li>
    <a href="http://localhost/laravel-fork/public/browser/">browser</a> 
    <span class="divider">/</span>
</li>
<li>
    <a href="http://localhost/laravel-fork/public/browser/admin/">administration</a> 
    <span class="divider">/</span>
</li>
<li class="active">no whatever please :P</li>

```

### B) Diving in a bit more:

#### Generating breadcrumbs from the current URI with a dash as separator:
You can do this with `echo Breadcrumb::make('html', null, null, '-');` on the current URL

```php
<a href="http://localhost/laravel-fork/public/browser/">browser</a> - <a href="http://localhost/laravel-fork/public/browser/firefox-is-pro/">a great browser</a> - poke
```

#### Generating breadcrumbs with custom attributes from custom source:
To achieve this you will need to call two methods in the process:

- First translate a custom array**:

`Breadcrumb::translate(array('users', 'add'));`.

- Then write the results to the output while providing an array with the formatting parameters\*** into the `make()` method

`echo Breadcrumb::make('html', Breadcrumb::dump(), array('class' => 'breadcrumb_link_class'));`

```php
// generated html code:
<a class="breadcrumb_link_class" href="http://localhost/laravel-fork/public/users/">users</a> / add
```
\** Always do a translate first, otherwise generate_html will use the current URI

\*** Formatting links is achieved the exact same way as it is done with normal code in a Laravel project. Please check http://laravel.com/docs/views/html#links for other examples.

#### Replacing first element of the current URI with a custom one:
This is done by two steps as well:

- First remove the unwanted element with `Breadcrumb::remove(0);`

- Then add you own element, like `Breadcrumb::append('Home');`

```php
// The URL before replacement: http://localhost/laravel-fork/public/users/list
// 
// generated html code after replacement:
<a class="breadcrumb_link_class" href="http://localhost/laravel-fork/public/home/">Main page</a> / Same List
```

> Please note that trying to remove not existing elements will produce an exception. Also if you are removing elements in a loop, you may want to check out the method it self in the bundle, since handling indexes may differ in each task you are working with and you have a second parameter which could be used in these cases!

>You can append as many elements as you want of course.

