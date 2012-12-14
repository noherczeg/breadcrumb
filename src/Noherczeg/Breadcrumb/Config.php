<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;

class Config
{

    private $configs = null;

    public function __construct()
    {
        $config_file = __DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';

        // Load configuration
        if (!file_exists($config_file)) {
            throw new FileNotFoundException("Can not load the config file!");
        } else {
            $this->configs = require $config_file;
        }
    }

    public function value($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Invalid argument provided, string required!");
        } elseif (!array_key_exists($key, $this->configs)) {
            throw new InvalidArgumentException("There is no " . $key . " key in the Configurations!");
        } else {
            return $this->configs[$key];
        }
    }

    public function dump()
    {
        return $this->configs;
    }

}
