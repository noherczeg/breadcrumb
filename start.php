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

// Autoload classes
Autoloader::namespaces(array(
    'Breadcrumb' => Bundle::path('breadcrumb'),
));

// Set the global alias for Breadcrumb
Autoloader::alias('Breadcrumb\\Breadcrumb', 'Breadcrumb');
Autoloader::alias('Breadcrumb\\BreadcrumbException', 'BreadcrumbException');

Breadcrumb::_init();
