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
* Can make twitter bootstrap breadcrumbs
* Segments can be ignored in any number from both sides

###Code examples

* Generating breadcrumbs from current URI:

```php
<?php

echo Breadcrumb::make();

// generated html code is:
// <a href="http://localhost/laravel-fork/public/admin/">Administration</a> / Cron jobs
```
Since no parameters where given, it generated the links, etc.. according to the default settings

* Generating breadcrumbs with custom attributes from custom source:

```php
<?php

Breadcrumb::translate(array('users', 'add'));
echo Breadcrumb::make('html', Breadcrumb::dump(), array('class' => 'breadcrumb_link_class'));

// generated html code:
// <a class="breadcrumb_link_class" href="http://localhost/laravel-fork/public/users/">users</a> / add
```
always do a translate first, otherwise generate_html will use the current URI

* Dumping a translated array from the URI:

```php
<?php

var_dump(Breadcrumb::dump());

// makes:
// Array ( [0] => browser [1] => a great browser )
```
* Doing the same, but with JSON array as result:

```php
<?php

var_dump(Breadcrumb::dump('json'));

// returned:
// ["browser","a great browser"]
```
* Generating breadcrumbs from the current URI with a dash as separator:

```php
<?php

// current URI, no extra attributes, dash as separator
echo Breadcrumb::make('html', null, null, '-');

// produces:
// <a href="http://localhost/laravel-fork/public/browser/">browser</a> - <a href="http://localhost/laravel-fork/public/browser/a great browser/">a great browser</a> - poke
```
* Generating twitter bootstrap breadcrumbs from the current URI:

```php
<?php

echo Breadcrumb::make('bootstrap');

// produces:
// <li>
//     <a href="http://localhost/laravel-fork/public/browser/">browser</a> 
//     <span class="divider">/</span>
// </li>
// <li>
//     <a href="http://localhost/laravel-fork/public/browser/admin/">administration</a> 
//     <span class="divider">/</span>
// </li>
// <li class="active">poke</li>

```

Please check the main class for further options :)

Enjoy!