##General usage examples:
###1) Preparing the Package:

__construct($base_url, $use_language)__
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

###2) Preparing data to work with

####a) Registering a batch of segments from variable input types:
__Warning__!
+ As of build 2.0.0 this method no longer tries to set the first element as a root element! If you'd like to add one (which points to the base url of your site), scroll down a bit and you'll get an example how to do it.
+ All of the given segments will be translated if the translator has a match in the provided dictionary!

__from($input)__
+ `$input (mixed|required)`: The source (URI, or hand made) from where to use segments.

Supported sources: `Array`, `JSON Array`, `String`
```
// Using a PHP array to fill our list
$bc->from(array('test-segment', 'second', '3rd', 'Fourth-Thingy'));

// or JSON array
$bc->from('["asds", "12323", "Qw-Er-Ty"]');

// or URI String
$bc->from('First-Segment/Second/3rdWhatever');
```

####b) Registering Segments piece by piece:
+ You can add segments one by one, and you can also tell Breadcrumb which side to put them in. This is useful when you are processing the data piece by piece and you don't necessary know the order of the final result at the start.
+ You can now even tell Breadcrumb if you con't want to translate a particular element, or if you want it to be a specific value

__append($raw_name, $side, $base, $translate)__

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

####c) Adding a segment which points to the base url:
Since the latest build (2.0.0) a major logic problem has been fixed. Now from whatever source you've used to seed Breadcrumb doesn't automatically sets the first element as a root element. This fixed the issue with the links! From now on if you want to add an element which points to the base url, you have to append one whenever you'd like to:
```
// this way you'll have a first element 'welcome' which will point to your base url
$bc->from('/action/parameter1/parameter2')->append('welcome', 'left', true);
```


####d) Removing Segments from the list:
You can remove segments one by one from the registered bunch.

__`remove($pos, $reindex)`__
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

####e) Overwriting translation:
Since: 2.0.2
```
// this way you can tell the translator to translate the given value to "overwritten" no matter what
$bc->append(URI::segment(3), 'left', true, 'overwritten');
```

###3) Building breadcrumbs:
Creating breadcrumbs can be simple or complicated as well if you desire. The package also lets you define your own builders as well (examine the Builders folder if you want to).

__`build($format, $casing, $last_not_link, $separator, $customizations)`__
+ `$format (String)`: The format of the created breadcrumbs (`bootstrap`, `foundation`, `html`)
+ `$casing (mixed)`: Sets the casing of the text in the breadcrumbs (`null`, `upper`, `lower`, `title`, default is `null`, which means no transformation is done)
+ `$last_not_link (Boolean)`: If set to false, then the last element will be a link as well, by default it's `true`
+ `$separator (String)`: The separator in between breadcrumbs (applied only to html and bootstrap stlye breadcrumbs, since there is no separator in Zurb Foundation!)
+ `$customizations (Array)`: An array of customizations can be set to elements where the keys are the properties and the values are the values (only works with html type breadcrumbs!)

####First we start out by adding some elements to work with
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
echo $bc->build();
```

####b) Creating _Zurb Foundation_ style breadcrumbs:
```
/**
 * This will build Zurb Foundation styled breadcrumbs with all links upper cased, and
 * the last element will be a link too
 */
echo $bc->build('foundation', 'upper', false);
```

####c) Creating _Bootstrap_ style breadcrumbs:
```
/**
 * This way you'll get Bootstrap styled breadcrumbs with unmodified casing, the last
 * element as link, and a "+" as separator
 */
echo $bc->build('bootstrap', null, false, '+');
```

####d) Creating complex _HTML_ breadcrumbs:
```
/**
 * This will create HTML breadcrumbs which will have lower cased links, last
 * element won't be a link, default separator will be ` / `, and will inject properties
 * which are in the array.
 */
echo $bc->build('html', 'lower', true, null, array('style' => 'font-family: Arial; font-size: 11px;', 'class' => 'some_class'));

```

###7) Utility functions:
These functions exist to somewhat ease your work with this package.

+ List of registered Segments: `var_dump($bc->registered());`
+ Picking one element from the list: `var_dump($bc->segment(1));`
+ Total number of Segments in the list: `var_dump($bc->num_of_segments());`