<?php

/**
 * Breadcrumb bundle for Laravel.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * available at the following URL:
 * http://www.opensource.org/licenses/BSD-3-Clause
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
use URI;
use HTML;

class BreadcrumbException extends \Exception
{
	
}

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
	final private function __construct()
	{
		
	}

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
	 * @param   string|array  	Whatever :)
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
	 * You can also set it so it cuts any number of elements from either side 
	 * of the array.
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

		if (strlen($format) == 0 || is_null($format))
			$format = Config::get('breadcrumb::breadcrumb.dump_format');

		if (!empty(static::$segments_translated))
		{
			// temporal variables
			$final_array = array();
			$max = count(static::$segments_translated) - 1;

			// ignore not needed segments
			for ($key = 0 + $slice_to_left; $key <= $max - $slice_from_right; $key++)
			{
				$final_array[] = static::$segments_translated[$key];
			}

			// decide what to dump as output
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
	 * @param  string|array     	Input element
	 * @param  string 				The casing of the breadcrumb segments
	 * @throws BreadcrumbException
	 * @return void
	 */
	public static function translate($input = null, $casing = null, $scan_bundles = false)
	{

		// Defaults
		if (strlen($scan_bundles) == 0 || $scan_bundles === false)
			$scan_bundles = Config::get('breadcrumb::breadcrumb.scan_bundles');

		if (strlen($casing) == 0 || is_null($casing))
			$casing = Config::get('breadcrumb::breadcrumb.default_casing');

		// Check if an input was given or not and process it if it is necessary
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
				$key = 'breadcrumb::breadcrumb.' . $value;
				$tmp = null;

				// If the scanning is turned on, and if we find a match
				// for the current controller's name in the bundles list
				// then we use it's language settings instead of this
				// bundle's translations.
				$controller_name = strtolower(URI::segment(1));

				// Case insensitive search, just in case... o.O
				foreach(\Bundle::names() AS $bundle_name)
				{

					// This isn't the greates way of executing a search,
					// but couldn't find a better way to do it at the 
					// time this was made....
					if(strtolower($controller_name) == strtolower($bundle_name) && $scan_bundles === true && Lang::has($bundle_name . '::breadcrumb.' . $value))
					{
						$tmp = Lang::line($bundle_name . '::breadcrumb.' . $value)->get();
					}
				}

				// If it doesn't find a match, then it basically continues
				// with the translation search in this bundle or falls back.
				if(is_null($tmp))
				{
					if (Lang::has($key))
					{
						$tmp = Lang::line($key)->get();
					}
					else
					{
						$tmp = $value;
					}
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
	 * The generator can generate breadcrumbs in an instant if you just call it 
	 * by itself. If you coose this, it'll generate content for the current URI 
	 * with the default settings.
	 *
	 * - The output format can be either plain html, or bootstrap style. If
	 * bootstrap is selected, then any extra attribute will be ignored!
	 *
	 * - The source: should be a translated dump (either a PHP array or JSON 
	 * array) or left null.
	 *
	 * - Extra atrribute: can be an array which normaly you'd pass to Laravel's 
	 * HTML::link() method.
	 * 
	 * - A separator: should be a single caharacter or a string.
	 *
	 * - Last not link: is a toggler which makes you able to choose if you want 
	 * the last segment be a link or just a plain string. At default it is set 
	 * to true = plain string.
	 *
	 * @param  string 		output format
	 * @param  array     	The source array. Either dumped, or null
	 * @param  array 		Array of attributes for the link's tag
	 * @param  string 		Separator character, or string
	 * @param  string 		Base url of the links
	 * @param  bool 		Is the last element a link or plain string
	 * @throws BreadcrumbException
	 * @return string
	 */
	public static function make($format = null, $source = null, $extra_attrib = null, $separator = null, $last_not_link = true)
	{

		/**
		 * Possible output formats
		 */
		$formats = array('html', 'bootstrap');

		/**
		 * Setting up working variables, etc for the job
		 */
		$pretty_result = '';
		$tmp_uri = '';

		/**
		 * Handling nulled parameters
		 */
		if (is_null($separator))
			$separator = Config::get('breadcrumb::breadcrumb.separator');

		if (is_null($format) || !in_array($format, $formats))
			$format = Config::get('breadcrumb::breadcrumb.output_format');

		if (!is_array($extra_attrib))
			$extra_attrib = null;

		/**
		 * Setting up the working array which we will use to generate the 
		 * breadcrumb as a HTML string with links, etc..
		 */
		try
		{
			$working_array = static::prepare_source($source);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}

		/**
		 * Generating the HTML/bootstrap string using Laravel's link builder.
		 *
		 * Notice that you can even add html attributes, as it was in the 
		 * parameters the last element is a simple string, or a link it self.
		 */
		end($working_array);
		$last_key = key($working_array);

		// html output
		if ($format == 'html')
		{
			$pretty_result .= static::genereate_html($working_array, $separator, $last_key, $last_not_link, $tmp_uri, $extra_attrib);
		}

		// twitter bootstrap output
		else
		{
			$pretty_result .= static::genereate_bootstrap($working_array, $separator, $last_key, $tmp_uri);
		}

		return $pretty_result;
	}

	/**
	 * Prepares the inserted source for further use
	 */
	protected static function prepare_source($source)
	{
		$result_array = null;

		if (is_array($source))
		{
			$result_array = $source;
		}
		elseif (json_decode($source) != null)
		{
			$result_array = array_values(json_decode($source));
		}
		elseif (is_null($source))
		{
			$result_array = static::$segments_translated;
		}
		else
		{
			throw new BreadcrumbException('Can\'t prepare source, something went wrong!');
		}

		return $result_array;
	}
	
	protected static function genereate_html($working_array, $separator, $last_key, $last_not_link, &$tmp_uri, $extra_attrib)
	{
		$result = null;

		/**
		 * Handling nulled parameters
		 */
		if (is_null($separator))
			$separator = Config::get('breadcrumb::breadcrumb.separator');
		
		foreach ($working_array AS $key => $segment)
		{
			if ($key > 0)
			{
				$result .= ' ' . trim($separator) . ' ';
			}

			if ($key == $last_key && $last_not_link == true)
			{
				$result .= $segment;
			}
			else
			{
				$tmp_uri .= static::$segments_raw[$key] . '/';
				$result .= HTML::link($tmp_uri, $segment, $extra_attrib);
			}
		}
		
		return $result;
	}
	
	protected static function genereate_bootstrap($working_array, $separator, $last_key, &$tmp_uri)
	{
		$result = null;
		
		foreach ($working_array AS $key => $segment)
		{
			if ($key == $last_key)
			{
				$result .= '<li class="active">' . $segment . '</li>';
			}
			else
			{
				$tmp_uri .= static::$segments_raw[$key] . '/';
				$result .= '<li>' . HTML::link($tmp_uri, $segment) . ' <span class="divider">' . trim($separator) . '</span></li>';
			}
		}
		
		return $result;
	}

}
