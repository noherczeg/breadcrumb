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

return array(

	/**
	 * Default casing to use on the output's segments.
	 *
	 * Uses the Laravel Str class's method names.
	 */
	'default_casing' => 'lower',

	/**
	 * Default output format.
	 *
	 * You can choose from: array, josn.
	 */
	'output_format' => 'array',

	/**
	 * Link separator, ignores whitespaces! A built in white space is
	 * inserted on both sides of the separator automatically.
	 */
	'separator'		=> '/'
);
