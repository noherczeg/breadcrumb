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
* Can produce fully formatted html breadcrumbs which can be:
* formatted, let it be either html attributes or casing options
* Segments can be ignored in any number for both sides

###Code examples

* Generating braedcrumb from current URI:

```php

<?php
// no parameters given, so it generated the links, etc.. according to the default settings
echo Breadcrumb::generate_html();
```
* Generating braedcrumb with custom attributes from custom source:

```php

<?php
// always do a translate first, otherwise generate_html will use the current URI
Breadcrumb::translate(array('users', 'add'));
echo Breadcrumb::generate_html(Breadcrumb::dump(), array('class' => 'breadcrumb_link_class'));
```
* Dumping a translated array from the URI:

```php

<?php
// dumps the current URI
var_dump(Breadcrumb::dump());
```
* Doing the same, but with JSON array as result:

```php

<?php
// dumps the current URI in json array format
var_dump(Breadcrumb::dump('json'));
```
* Generating breadcrumbs from the current URI with a dash as separator:

```php

<?php
// current URI, no extra attributes, dash as separator
echo Breadcrumb::generate_html(null, null, '-');
```

Please check the main class for further options :)

Enjoy!