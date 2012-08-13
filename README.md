# Breadcrumb

Breadcrumb is a simple bundle made for the [Laravel framework](http://laravel.com/)

###Quickstart

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
* The bundle uses the *APPPATH/application/config/application.php* file to determine which language profile to load from the bundle's language folder
* No migration needed

###Features

* Uses language files to translate breadcrumb segments
* Segments can be URI segments or custom arrays
* Can dump the results in either PHP arrays, or JSON arrays
* Can produce full html breadcrumbs which can be formatted, let it be either html attributes or casing options
* Segments can be ignored in any number from both sides

###Code examples

* Generating breadcrumbs from current URI:

```php

<?php
// URL: http://localhost/laravel-fork/public/admin/cron
// no parameters given, so it generated the links, etc.. according to the default settings
echo Breadcrumb::generate_html();

// generated html code is:
// <a href="http://localhost/laravel-fork/public/admin/">Administration</a> / Cron jobs
```
* Generating breadcrumbs with custom attributes from custom source:

```php

<?php
// always do a translate first, otherwise generate_html will use the current URI
Breadcrumb::translate(array('users', 'add'));
echo Breadcrumb::generate_html(Breadcrumb::dump(), array('class' => 'breadcrumb_link_class'));

// generated html code:
// <a class="breadcrumb_link_class" href="http://localhost/laravel-fork/public/users/">users</a> / add
```
* Dumping a translated array from the URI:

```php

<?php
// URL: http://localhost/laravel-fork/public/browser/firefox
// dumps the current URI
var_dump(Breadcrumb::dump());

// makes:
// Array ( [0] => browser [1] => a great browser )
```
* Doing the same, but with JSON array as result:

```php

<?php
// dumps the current URI in json array format
var_dump(Breadcrumb::dump('json'));

// json array is returned
// ["browser","a great browser"]
```
* Generating breadcrumbs from the current URI with a dash as separator:

```php

<?php
// URL: http://localhost/laravel-fork/public/browser/admin/poke
// current URI, no extra attributes, dash as separator
echo Breadcrumb::generate_html(null, null, '-');

// produces
// <a href="http://localhost/laravel-fork/public/browser/">browser</a> - <a href="http://localhost/laravel-fork/public/browser/a great browser/">a great browser</a> - poke
```

Please check the main class for further options :)

Enjoy!