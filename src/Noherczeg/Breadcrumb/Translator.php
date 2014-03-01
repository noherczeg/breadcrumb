<?php namespace Noherczeg\Breadcrumb;
use InvalidArgumentException;

/**
 * Translator
 * 
 * The sole job of this class is to properly translate (or not) the given value
 * according to the configurations.
 */

class Translator
{

    private $dictionary = null;
    private $language_folder = null;
    private $config = null;

    /**
     * Constructor
     * 
     * @param String	$config	The code(file name in the Languages folder),
     *                                  or an array of key-value pairs of the
     *                                  language to use for translations
     */
    public function __construct($config = null)
    {
        
        // load the configs
        $this->config = new \Noherczeg\Breadcrumb\Config();

        // set up folder path
        $this->language_folder = __DIR__ . DIRECTORY_SEPARATOR . 'Languages' . DIRECTORY_SEPARATOR;

        // try to load a dictionary
        try {
            if (is_array($config) && array_key_exists('language', $config)) {
                // loads a dictionary if it gets a full-blown config array as param and it contains the "language" key in it
                $this->dictionary = $this->loadDictionary($config['language']);
            } elseif(is_array($config)) {
                // if it's a simple array, then it sets it as key-value pair
                $this->dictionary = $config;
            } elseif(is_string($config)) {
                // if it's a simple string it tries to load a dictionary as if it would be the dictionary's name
                $this->dictionary = $this->loadDictionary($config);
            } else {
                // fallback
                $this->dictionary = $this->loadDictionary($this->config->value('language'));
            }
        } catch (InvalidArgumentException $a) {
            echo 'Translator Exception: ' . $a->getMessage();
        } catch (FileNotFoundException $f) {
            echo 'Translator Exception: ' . $f->getMessage();
        }
    }

    /**
     * loadDictionary: loads a language array from the Language folder.
     * 
     * @param String $lang      Selected language's code e.g. 'en'
     * @return array
     * @throws \InvalidArgumentException
     */
    public function loadDictionary($lang = null)
    {
        if (!is_string($lang) && !is_null($lang)) {
            throw new \InvalidArgumentException("Please provide a string as parameter!");
        } else {
            
            // if nothing is set, get it from the config file
            if (is_null($lang)) {
                $lang = $this->config->value('language');
            }
            
            $file_to_load = $this->language_folder . $lang . '.php';

            return $this->loadFile($file_to_load);
        }
    }

    /**
     * loadFile: yeah, well, separated due to testing purposes :(
     * 
     * @param String $file_to_load      Language file's name
     * @return array
     * @throws \Noherczeg\Breadcrumb\FileNotFoundException
     */
    public function loadFile($file_to_load = null)
    {
        if (!file_exists($file_to_load)) {
            throw new \Noherczeg\Breadcrumb\FileNotFoundException("Can not load the requested language file: $file_to_load!");
        } else {
            return require $file_to_load;
        }
    }

    /**
     * translate: Fetches a value from the Ditionary with an index provided.
     * 
     * @param String $key   Parameter which we want to translate
     * @return String
     * @throws \InvalidArgumentException
     */
    public function translate($key)
    {
        // inproper param given
        if (!is_string($key) && !is_int($key)) {
            throw new \InvalidArgumentException("Invalid argument provided, string required!");

        // value provided not found in dictionary -> return it with slug
        // converted to a space
        } elseif (!array_key_exists($key, $this->dictionary)) {
            return preg_replace('/\\' . $this->config->value('slug_separator') . '/', ' ', $key);

        } else {

            // return with the translated value
            return $this->dictionary[$key];
        }
    }

    /**
     * dump: Dumps the contents of the Dictionary.
     * 
     * @return array
     */
    public function dump()
    {
        return $this->dictionary;
    }

}
