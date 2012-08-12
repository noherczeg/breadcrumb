<?php
/**
 * Breadcrumb bundle for Laravel.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Breadcrumb
 * @version    1.0
 * @author     Norbert Csaba Herczeg
 * @license    BSD License (3-clause)
 * @copyright  (c) 2012, Norbert Csaba Herczeg
 */

namespace Breadcrumb;

use Config;
use Lang;
use Str;

class BreadcrumbException extends \Exception {}

/**
 * Breadcrumb class
 */
class Breadcrumb
{

	/**
	 * @var  array  raw segments before translation
	 */
	protected static $segments_raw = array();

	/**
	 * @var  array  segments after translation
	 */
	protected static $segments_translated = array();

	/**
	 * Prevent instantiation
	 */
	final private function __construct() {}

	/**
	 * Run when class is loaded
	 *
	 * @return  void
	 */
	public static function _init()
	{
		/**
		 * start off with using the current URI and default
		 * settings
		 */
		static::translate();

	}

	/**
	 * Splits (if it needs to) the given input into an array.
	 *
	 * @param   int|string|array  Whatever :)
	 * @return  array
	 */
	private static function split_uri($input)
	{
		$pos = strpos($input, '/');

		if ($pos !== false)
		{
			return explode('/', $input);
		}

		return array($input);
	}

	/**
	 * Dumps the segments in a selectable format.
	 *
	 * You can also set it so it cuts any number of elements from
	 * either side of the array.
	 *
	 * @param   string  The format of the output
	 * @param   int     number of elements to cut from the left
	 * @param   int     number of elements to cut from the right
	 * @throws  BreadcrumbException
	 * @return  array|json array
	 */
	public static function dump($format = null, $slice_to_left = 0, $slice_from_right = 0)
	{
		$result_formatted = null;

		if(strlen($format == 0) || is_null($format))
			$format = Config::get('breadcrumb::breadcrumb.output_format');

		if (!empty(static::$segments_translated))
		{
			// temporal variables
			$final_array = array();
			$max = count(static::$segments_translated) - 1;

			// ignore the not needed segments
			for ($key = 0 + $slice_to_left; $key <= $max - $slice_from_right; $key++)
			{
				$final_array[] = static::$segments_translated[$key];
			}

			// decide how to display output
			switch ($format)
			{
				case 'json':
					$result_formatted = json_encode($final_array);
					break;
				default:
					$result_formatted = $final_array;
					break;
			}

			return $result_formatted;
		}
		else
		{
			Throw new BreadcrumbException('Nothing to dump!');
		}
	}

	/**
	 * Translates the input segments if it finds a match for them in the
	 * language files, if not, it leaves them as they are.
	 *
	 * @param  int|string|array     Input element
	 * @param  string 				The casing of the breadcrumb segments
	 * @throws BreadcrumbException
	 * @return void
	 */
	public static function translate($input = null, $casing = '')
	{
		if(strlen($casing == 0))
			$casing = Config::get('breadcrumb::breadcrumb.default_casing');

		// Check if an input was given or not and process it if it's necessary
		if (is_array($input))
		{
			static::$segments_raw = $input;
		}
		elseif ($input != null)
		{
			static::$segments_raw = static::split_uri($input);
		}
		else
		{
			static::$segments_raw = static::split_uri(URI::current());
		}

		// Translation
		if (is_array(static::$segments_raw) && !empty(static::$segments_raw))
		{
			// Clean previous versions
			static::$segments_translated = null;

			foreach (static::$segments_raw AS $value)
			{
				$key = 'breadcrumb.' . $value;
				$tmp = '';

				// If the segment is in the language file it loads it, otherwise
				// keeps it unchanged
				if (Lang::has($key))
				{
					$tmp = Lang::line($key)->get();
				}
				else
				{
					$tmp = $value;
				}

				// Formats
				switch ($casing)
				{
					case 'lower':
						$tmp = Str::lower($tmp);
						break;
					case 'upper':
						$tmp = Str::upper($tmp);
						break;
					case 'title':
						$tmp = Str::title($tmp);
						break;
					default:
						$tmp = Str::lower($tmp);
				}

				static::$segments_translated[] = $tmp;
			}
		}
		else
		{
			throw new BreadcrumbException('No array provided to work with!');
		}
	}

	/**
	 * Generates HTML string containing links separated as you wish.
	 *
	 * The generator can generate breadcrumbs in an instant if you
	 * just call it by itself. If you coose this, it'll generate
	 * content for the current URI with the default settings.
	 *
	 * - The source should be a translated dump (either a PHP array
	 * or JSON array) or left null.
	 *
	 * - Extra atrribute can be an array which normaly you'd pass to
	 * Laravel's HTML::link() method.
	 * 
	 * - Separator should be a single caharacter or a string.
	 *
	 * - Base URL can be left as is (null) then it is handled by
	 * calling URL::base(), or you can specify any URL for it.
	 *
	 * - Last not link is a toggler which makes you able to choose
	 * if you want the last segment be a link or just a plain string.
	 * At default it is set to true = plain string.
	 *
	 * @param  null|array     	The source array. Either dumped, or null
	 * @param  null|array 		Array of attributes for the link's tag
	 * @param  null|string 		Separator character, or string
	 * @param  null|string 		Base url of the links
	 * @param  bool 			Is the last element a link or plain string
	 * @throws BreadcrumbException
	 * @return string
	 */
	public static function generate_html($source = null, $extra_attrib = null, $separator = null, $base_url = null, $last_not_link = true)
	{
		/**
		 * Setting up working variables, etc for the job
		 */
		$pretty_result = '';
		$tmp_uri = '';
		$working_array = array();
		$json_test = json_decode($source);

		/**
		 * Handling nulled parameters
		 */
		if(is_null($separator))
			$separator = Config::get('breadcrumb::breadcrumb.separator');

		if(is_null($base_url))
			$base_url = URL::base();

		if(!is_array($extra_attrib))
			$extra_attrib = null;

		/**
		 * Setting up the working array which we will use to generate
		 * the breadcrumb as a HTML string with links, etc..
		 */
		if(is_array($source))
		{
			$working_array = $source;
		}
		elseif($json_test != null)
		{
			$working_array = $json_test;
		}
		elseif(is_null($source))
		{
			$working_array = static::segments_translated;
		}
		else
		{
			throw new BreadcrumbException('Cant\'t make pretty urls, no proper array found to work with');
		}

		/**
		 * Generating the HTML string using Laravel's link builder.
		 *
		 * Notice that you can even add html attributes, as it was in
		 * the parameters the last element is a simple string, or a
		 * link it self too.
		 */
		foreach($working_array AS $key => $segment)
		{
			$tmp_uri .= $segment . '/';

			if($key == end($working_array) && $last_not_link == true)
			{
				$pretty_result .= ' ' . $separator . ' ' . $segment;
			}

			$pretty_result .= $pretty_result .= ' ' . $separator . ' ' . HTML::link($tmp_uri, $segment, $extra_attrib);
		}

		return $pretty_result;
	}

}
