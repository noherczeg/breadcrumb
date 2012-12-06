<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;

class Translator
{
    private $dictionary = null;
    private $language_folder = null;

    public function __construct ($use_language = 'en')
    {
        $this->language_folder = __DIR__ . DIRECTORY_SEPARATOR . 'Language' . DIRECTORY_SEPARATOR;

        try {
            $this->dictionary = $this->loadDictionary($use_language);
        } catch (InvalidArgumentException $a) {
            echo 'Translator Exception: ' . $a->getMessage();
        } catch (FileNotFoundException $f) {
            echo 'Translator Exception: ' . $f->getMessage();
        }

    }

    public function loadDictionary ($lang = 'en')
    {
        if (!is_string($lang)) {
            throw new InvalidArgumentException("Please provide a string as parameter!");
        } else {
            $file_to_load = $this->language_folder . $lang . '.php';

            if (!file_exists($file_to_load)) {
                throw new FileNotFoundException("Can not load the requested language file: $lang!");
            } else {
                return require $file_to_load;
            }
        }
    }

    public function translate ($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Invalid argument provided, string required!");
        } elseif (!array_key_exists($key, $this->configs)) {
            throw new InvalidArgumentException("There is no " . $key . " key in the Dictionary!");
        } else {
            return $this->dictionary[$key];
        }
    }

    public function dump ()
    {
        return $this->dictionary;
    }
}