#Breadcrumb

A package for breadcrumb management.

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

##Changelog:

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

##Usage examples:
###1) Instantiation of the class:
First parameter should be for example passed from an external framework's base_url() or equivalent method, but can be written manually too:
```
// URL::base() = 'http://localhost/breadcrumb/'
$bc = new \Noherczeg\Breadcrumb\Breadcrumb(URL::base());
```
Using non default(en) language file:
```
$bc = new \Noherczeg\Breadcrumb\Breadcrumb('http://localhost/breadcrumb/', 'de');
```

###2) Registering segments via variable input types:
Using `from($input)`, where `$input` can be either: a `JSON array`, a `PHP array`, or an `URI String`.
```
// PHP array
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));

// or JSON array
$bc->from('["asds", "12323", "Qw-Er-Ty"]');

// or URI String
$bc->from('First-Segment/Second/3rdWhatever');
```

###3) Registering Segments piece by piece:
This example represents the thing that placing Segments can be done in separate order, the only thing that matters is positioning!

Using `append($name, $side, $base)`, where:

+ `$name` is the name of the Segment
+ `$side` is the side where to place the Segment (`left`/`right`)
+ `$base` is this element an element which points to the base URL of the site (`true`/`false`)

_We now use method chaining as well, but it's just an option :)_
```
/**
 * The order in which the Segments will be stored in the list is: first, second, third, fourth.
 */
$bc->append('third');
$bc->append('fourth', 'right');
$bc->append('second', 'left')->append('first', 'left', true);

```

###4) Removing Segments from the list:
Using `remove($pos, $reindex)`, where:

+ `$pos` is the position of the element which we want to remove, `int`
+ `$reindex` is a boolean value which may trigger a re-index after removing the desired element, `true`/`false`

```
// we provide a base list of Segments before removal
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));

// remove the Segment on index: 2
$bc->remove(2);

// remove the Segment on index: 1, and re-index the list after
$bc->remove(1, true);

/**
 * Result will be (filtered list, not actual var_dump!)
 * 0 => 'test_segment'
 * 1 => 'Fourth-Thingy'
 */
```

###5) Building breadcrumbs:
via `build($format, $casing, $last_not_link, $separator, $customizations)` where:

+ `$format` is the desired format of the output, can be: `html`/`bootstrap`/`foundation` by default
+ `$casing` is the casing of the translated breadcrumb elements, can be: `null`/`upper`/`lower`/`title`
+ `$last_not_link` is a boolean value, enables/disables the link on a segment, `true`/`false`
+ `$separator` will be a custom `String` which will be the separator between the breadcrumbs, won't be trimmed! __(Bootstrap AND HTML ONLY!)__
+ `$customizations` will be an array of optional properties __(HTML ONLY)__

####First I'll start out with adding some elements to work with
```
// Sample list initialization
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));
```

####a) Creating breadcrumbs with _default_ settings:
```
/**
 * This will build default format(html) breadcrumbs with the default separator( / )
 * without any casing modification or property injection, and the last element
 * won't be a link
 */
$bc->build();
```

####b) Creating _Zurb Foundation_ style breadcrumbs:
```
/**
 * This will build Zurb Foundation styled breadcrumbs with all links upper cased, and
 * the last element will be a link too
 */
$bc->build('foundation', 'upper', false);
```

####c) Creating _Bootstrap_ style breadcrumbs:
```
/**
 * This way you'll get Bootstrap styled breadcrumbs with unmodified casing, the last
 * element as link, and a "+" as separator
 */
$bc->build('bootstrap', null, false, '+');
```

####d) Creating complex HTML breadcrumbs:
```
/**
 * This will be HTML styled breadcrumb which will have lower cased links, last 
 * element won't be a link, default separator ( / ), and will inject properties 
 * which are in the array.
 */
$bc->build('html', 'lower', true, null, array('style' => 'font-family: Arial; font-size: 11px;', 'class' => 'some_class'));

```

###6) Utility functions:

+ List of registered Segments: `var_dump($bc->registered());`
+ Picking one element from the list: `var_dump($bc->segment(1));`
+ Total number of Segments in the list: `var_dump($bc->num_of_segments());`

##Dev's note:
If you notice an error / logic failure, or know something that is missing, please let me know via pull requests or issues. I'm tracking this repo, and will do it forever, so you can reach me :)




[![Build Status](https://secure.travis-ci.org/noherczeg/breadcrumb.png?branch=develop)](https://travis-ci.org/noherczeg/breadcrumb)