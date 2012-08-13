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
* The bundle uses the *APPPATH/application/config/application.php* file to determine which language profile to load from the bundles's language folder
* No migration needed

###Features

* Uses language files to translate breadcrumb segments
* Segments can be URI segments or custom arrays
* Can dump the results in either PHP arrays, or JSON arrays
* Can produce fully formatted html breadcrumbs which can be:
* formatted, let it be either html attributes or casing options
* Segments can be ignored in any number for both sides

###Code examples

* Generating html string from current URI:
	```php
		<?php
		echo Breadcrumb::generate_html();
	```
* Generating customized html string with custom source:
	```php
		<?php
		Breadcrumb::translate(array('users', 'add'));
		echo Breadcrumb::generate_html(Breadcrumb::dump(), array('class' => 'breadcrumb_link_class'));
	```
* Dumping a translated array from the URI:
	```php
		<?php
		var_dump(Breadcrumb::dump());
	```
* Doing the same, but with JSON array as result:
	```php
		<?php
		var_dump(Breadcrumb::dump('json'));
	```
* Generating breadcrumbs from the current URI with a dash as separator:
	```php
		<?php
		echo Breadcrumb::generate_html(null, null, '-');
	```

Please check the main class for further options :)

Enjoy!