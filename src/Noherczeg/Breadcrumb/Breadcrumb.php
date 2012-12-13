<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;

/**
 * Breadcrumb
 *
 * Breadcrumb handler package.
 *
 * @package     Breadcrumb
 * @version     2.0
 * @author      Norbert Csaba Herczeg
 * @license     BSD License (3-clause)
 * @copyright   (c) 2012, Norbert Csaba Herczeg
 */

class FileNotFoundException extends \Exception {}

class Breadcrumb
{
    private $base_url   = null;
    private $segments   = array();
    private $translator = null;

    public function __construct ($base_url = null, $use_language = 'en')
    {
        // Set defaults
        is_null ($base_url) ? $base_url = './' : $base_url;

        // Set objet properties
        $this->base_url = $this->setParam($base_url);
        $this->language = $this->setParam($use_language);

        // Load Util Classes
        $this->translator = new Translator($use_language);
    }

    private function setParam ($to_this)
    {
        if (!is_string($to_this) && !is_null($to_this)) {
            throw new InvalidArgumentException("Please provide a string as parameter!");
        } else {
            return $to_this;
        }
    }

    public function append ($raw_name = null, $side = 'left')
    {
        if (!is_string($raw_name) && !is_int($raw_name) && !in_array($side, array('left', 'right'))) {
            throw new InvalidArgumentException("Malformed arguments provided!");
        } else {
            if ($side === 'left') {

                // Append to the left side
                array_unshift($this->segments, new Segment($raw_name));
            } else {

                // Append to the right side
                $this->segments[] = new Segment($raw_name);
            }

            return $this;
        }
    }

    public function remove ($pos = 0, $reindex_after_remove = false)
    {
        if (in_array($pos, array_keys($this->segments))) {
            unset($this->segments[$pos]);

            if ($reindex_after_remove) {
                $this->segments = array_values($this->segments);
            }

            return $this;
        } else {
            throw new InvalidArgumentException('Refering to non existent Segment position!');
        }
    }

    public function from ($input = null, $base_segment = null)
    {
        // If we can't process the input, throw an exception
        if (!is_string($input) && !is_array($input)) {
            throw new InvalidArgumentException("Invalid argument provided, string/array required!");
        } else {

            // if we provide a string as a base name for the base URl, or leave it alone
            if (is_string($base_segment)) {
                $this->append($base_segment);
            } elseif ($base_segment === null) {
                $this->append('wellcome');
            }

            // Container of raw strings
            $guaranteed_array = array();

            // PHP array
            if (is_array($input)) {
                $guaranteed_array = $input;
            }

            // URI string
            if (is_string($input)) {
                $guaranteed_array = preg_split('/\//', $input, -1, PREG_SPLIT_NO_EMPTY);
            }

            // JSON array
            if (json_decode($input) != null) {
                $guaranteed_array = array_values(json_decode($input));
            }

            // append all
            foreach ($guaranteed_array as $segment_raw_name) {
                $this->append($segment_raw_name);
            }

            // chaining support :)
            return $this;
        }
    }

    public function dump ()
    {
        return $this;
    }

    public function registered_segments()
    {
        return count($this->segments);
    }

    public function segment ($id)
    {
        if (in_array($id, array_keys($this->segments))) {
            return $this->segments[$id];
        } else {
            throw new InvalidArgumentException("Invalid argument provided, no segment is present with id: $id!");
        }
    }
}
