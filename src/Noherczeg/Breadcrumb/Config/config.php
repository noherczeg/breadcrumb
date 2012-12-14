<?php namespace Noherczeg\Breadcrumb\Config;
/**
 * Package configuration
 *
 * Please use possible values if they are listed!
 */

return array (

	/**
	 * Default casing to use on the output's segments.
	 *
	 * Possible values: lower, upper, title.
	 */
	'default_casing' => 'lower',

	/**
	 * Default array dump format.
	 *
	 * Possible values: php, json.
	 */
	'dump_format' => 'php',

	/**
	 * Default output format used by build().
	 *
	 * Possible values: html, bootstrap, foundation.
	 */
	'output_format' => 'html',

	/**
	 * Link separator, DOESN'T ignore whitespaces!
	 */
	'separator'	=> ' / ',

	/**
	 * Slug settings:
	 *
	 * 'slug_separator': Separator character in slugs.
	 */
	'slug_separator' => '-',

	/**
	 * Separator for words in language file keys. Can be any character
	 * or false. Doesn't have to be the same as the slug separator!
	 *
	 * For example in language files:
	 * - 'words_converted_from_slug' => 'Lorem ipsum dolor sit amet'
	 * - the key_separator has to be be "_".
	 */
	'key_separator' => '_'

);