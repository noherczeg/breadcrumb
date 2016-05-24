<?php namespace Noherczeg\Breadcrumb;

/**
 * Config
 * 
 * Configuration handler class for the package
 */

class Config
{

    private $configs = null;

    public function __construct(array $fromArray = array())
    {
        $configFile = realpath(__DIR__.'/../../config/config.php');

        // Load configuration
        if (!file_exists($configFile)) {
            throw new \FileNotFoundException("Can not load the config file!");
        } else {
            $builtIn = require $configFile;
            
            // If we provide an array of configurations we merge
            // them to our default values
            if(!empty($fromArray)) {
                $this->configs = array_merge($builtIn, $fromArray);
            } else {
                $this->configs = $builtIn;
            }
        }
    }

    /**
     * value: Returns the value of the requested key.
     * 
     * @param String $key       Requested value's key
     * @return String
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    public function value($key = null)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException("Invalid argument provided, string required!");
        } elseif(
            method_exists('\Illuminate\Config\Repository', 'get') &&
            method_exists('\Config', 'get') &&
            \Config::get('breadcrumb::' . $key, false) !== false
        ) {
            return \Config::get('breadcrumb::' . $key, false);
        } elseif (!array_key_exists($key, $this->configs)) {
            throw new \OutOfRangeException("There is no " . $key . " key in the Configurations!");
        } else {
            return $this->configs[$key];
        }
    }

    /**
     * dump: Returns all the configurations.
     * 
     * @return type
     */
    public function dump()
    {
        return $this->configs;
    }

}
