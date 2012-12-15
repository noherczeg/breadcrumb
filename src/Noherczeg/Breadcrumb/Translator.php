<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;

class Translator
{

    private $dictionary = null;
    private $language_folder = null;
    private $config = null;

    public function __construct($use_language = 'en')
    {
        $this->language_folder = __DIR__ . DIRECTORY_SEPARATOR . 'Language' . DIRECTORY_SEPARATOR;
        $this->config = new \Noherczeg\Breadcrumb\Config();

        try {
            $this->dictionary = $this->loadDictionary($use_language);
        } catch (InvalidArgumentException $a) {
            echo 'Translator Exception: ' . $a->getMessage();
        } catch (FileNotFoundException $f) {
            echo 'Translator Exception: ' . $f->getMessage();
        }
    }

    /**
     * loadDictionary: loads a language array from the Language folder.
     * 
     * @param String $lang
     * @return array
     * @throws InvalidArgumentException
     */
    public function loadDictionary($lang = 'en')
    {
        if (!is_string($lang)) {
            throw new InvalidArgumentException("Please provide a string as parameter!");
        } else {
            $file_to_load = $this->language_folder . $lang . '.php';

            return $this->loadFile($file_to_load);
        }
    }

    /**
     * loadFile: yeah, well, separated due to testing purposes :(
     * 
     * @param String $file_to_load
     * @return array
     * @throws FileNotFoundException
     */
    public function loadFile($file_to_load = null)
    {
        if (!file_exists($file_to_load)) {
            throw new FileNotFoundException("Can not load the requested language file: $file_to_load!");
        } else {
            return require $file_to_load;
        }
    }

    /**
     * translate: Fetches a value from the Ditionary with an index provided.
     * 
     * @param String $key
     * @return String
     * @throws InvalidArgumentException
     */
    public function translate($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Invalid argument provided, string required!");
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
