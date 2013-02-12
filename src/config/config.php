<?php namespace Noherczeg\Breadcrumb\Config;

return array (

	/**
	 * Default casing to use on the output's segments.
	 *
	 * Possible values: lower, upper, title, null (to leave slugs as they are).
	 */
	'casing' => null,

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
	 * Default language (from the Languages folder).
	 */
	'language' => 'en',

	/**
	 * Link separator, DOESN'T ignore whitespaces!
	 */
	'separator'	=> ' / ',

	/**
	 * Slug separator:
	 *
	 * Separator character in slugs. Keys in language files must be separated
         * by this as well!
	 */
	'slug_separator' => '-',

);