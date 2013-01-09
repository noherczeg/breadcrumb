<?php namespace Noherczeg\Breadcrumb\Facades;

use Illuminate\Support\Facades\Facade;

class Breadcrumb extends Facade {

/**
* Get the registered component.
*
* @return object
*/
protected static function getFacadeAccessor(){ return 'breadcrumb'; }

}
