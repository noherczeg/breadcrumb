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

return array(

	/**
	 * Default casing to use on the output's segments.
	 *
	 * Uses the Laravel Str class's method names.
	 */
	'default_casing' => 'lower',

	/**
	 * Default dump format.
	 *
	 * You can choose from: array, json.
	 */
	'dump_format' => 'array',
	
	/**
	 * Default output format used by make().
	 *
	 * You can choose from: html, bootstrap.
	 */
	'output_format' => 'html',

	/**
	 * Link separator, ignores whitespaces! A built in white space is
	 * inserted on both sides of the separator automatically.
	 */
	'separator'		=> '/',

	/**
	 * Sets if the the bundle should scan other bundles folders too
	 * for the language file breadcrumb.php.
	 *
	 * This does not ignore this bundle's language
	 * settings!
	 * 
	 * If set to true and other bundle languages match the segments, 
	 * then those will override this bundle's segments.
	 */
	'scan_bundles'	=> false,

	/**
	 * Slug settings:
	 *
	 * 'use_slug_with': Separator character in slugs, If it is set to:
	 *
	 * - anything except false (for example: "-")
	 *
	 * The bundle will automatically handle uri parts as if they
	 * are potential slugs with the given separator, also converts
	 * the separating characters into spaces on output.
	 * 
	 * - false
	 *  
	 * Slug handling will be ignored.
	 */
	'slug_separator' => '-',

	/**
	 * Separator for words in language file keys. Can be any character
	 * or false.
	 * 
	 * For example in language files:
	 * - 'words_converted_from_slug' => 'Lorem ipsum dolor sit amet'
	 * - the word_separator will be "_".
	 */
	'word_separator' => '_'

);
