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
	 * Possible values: lower, upper, title, null (to leave slugs as they are).
	 */
	'default_casing' => null,

	/**
	 * Default array dump format.
	 *
	 * Possible values: php, json.
	 */
	'dump_format' => 'php',

	/**
	 * Default output format used by build().
	 */
	'output_format' => 'html',
    
    /**
	 * Default language (from the Language folder).
	 */
	'default_language' => 'en',

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

);