<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;
use OutOfRangeException;

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

    private $base_url = null;
    private $segments = array();
    private $translator = null;
    private $config = null;
    private $build_formats = array('bootstrap', 'foundation', 'php');

    public function __construct($base_url = null, $use_language = 'en')
    {
        // Set defaults
        is_null($base_url) ? $base_url = './' : $base_url;

        // Set objet properties
        $this->base_url = $this->setParam($base_url);
        $this->language = $this->setParam($use_language);

        // Load Util Classes
        $this->translator = new Translator($use_language);

        $this->config = new Config();
    }

    /**
     * setParam: basic system method, don't bother.
     * 
     * @param mixed $to_this
     * @return String
     * @throws InvalidArgumentException
     */
    private function setParam($to_this)
    {
        if (!is_string($to_this) && !is_null($to_this)) {
            throw new InvalidArgumentException("Please provide a string as parameter!");
        } else {
            return $to_this;
        }
    }

    /**
     * append: Appends an element to the list of Segments. Can do it from both
     * sides, and can mark an element as base element, which means that it'll 
     * point to the base URL.
     * 
     * Supports method chaining.
     * 
     * Warning! It doesn't fix multiple "base element" issues, so it's up to the
     * programmer to append base elements wisely!
     * 
     * @param String $raw_name
     * @param String $side
     * @param boolean $base
     * @return \Noherczeg\Breadcrumb\Breadcrumb
     * @throws InvalidArgumentException
     */
    public function append($raw_name = null, $side = 'right', $base = false)
    {
        if (!is_string($raw_name) && !is_int($raw_name) && !in_array($side, array('left', 'right'))) {
            throw new InvalidArgumentException("Malformed arguments provided!");
        } else {

            // create segment
            $segment = new Segment($raw_name, $base);

            // create translated value
            $segment->setTranslated($this->translator->translate($raw_name));

            if ($side === 'left') {

                // Append to the left side
                array_unshift($this->segments, $segment);
            } else {

                // Append to the right side
                $this->segments[] = $segment;
            }

            return $this;
        }
    }

    /**
     * remove: Removes and element from the Segments registered, optionaly can
     * reindex the list after removal.
     * 
     * Supports method chaining.
     * 
     * @param int $pos
     * @param boolean $reindex_after_remove
     * @return \Noherczeg\Breadcrumb\Breadcrumb
     * @throws OutOfRangeException
     */
    public function remove($pos = 0, $reindex_after_remove = false)
    {
        if (in_array($pos, array_keys($this->segments))) {
            unset($this->segments[$pos]);

            if ($reindex_after_remove) {
                $this->segments = array_values($this->segments);
            }

            return $this;
        } else {
            throw new OutOfRangeException('Refering to non existent Segment position!');
        }
    }

    /**
     * from: reads the first parameter which can be a String, PHP array,
     * JSON array and creates + appends Segments from it in one step.
     * 
     * Supports method caining.
     * 
     * @param mixed $input
     * @return \Noherczeg\Breadcrumb\Breadcrumb
     * @throws InvalidArgumentException
     */
    public function from($input = null)
    {
        // If we can't process the input, throw an exception
        if (!is_string($input) && !is_array($input)) {
            throw new InvalidArgumentException("Invalid argument provided, string/array required!");
        } else {

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
            
            $counter = 0;

            // append all
            foreach ($guaranteed_array as $segment_raw_name) {
                if ($counter === 0) {
                    $this->append($segment_raw_name, 'left', true);
                } else {
                    $this->append($segment_raw_name);
                }
                
            }

            // chaining support :)
            return $this;
        }
    }

    /**
     * dump: dumps the list of Segments in the system.
     * 
     * @param String $format
     * @return array|json
     * @throws OutOfRangeException
     */
    public function dump($format = 'php')
    {
        if (in_array($format, array('json', 'php'))) {
            if ($format === 'json') {
                return json_encode($this->segments);
            } else {
                return $this->segments;
            }
        } else {
            throw new OutOfRangeException("Invalid argument provided, expected 'php', or 'json'");
        }
    }

    /**
     * registered_segments: returns the number of segments which are registered
     * in the system.
     * 
     * @return int
     */
    public function registered_segments()
    {
        return count($this->segments);
    }

    /**
     * segment: a getter whic hreturn the segment which has the given ID.
     * 
     * @param String $id The ID of the required Segment.
     * @return Segment 
     * @throws OutOfRangeException
     */
    public function segment($id)
    {
        if (in_array($id, array_keys($this->segments))) {
            return $this->segments[$id];
        } else {
            throw new OutOfRangeException("Invalid argument provided, no segment is present with id: $id!");
        }
    }
    
    /**
     * build: Builder method which returns with a result type as required.
     * Supports separator switching, casing switching, and custom tag property
     * insertion from an array.
     * 
     * @param String $format
     * @param String|null $separator
     * @param String|null $casing
     * @param array $customizations
     * @return String
     * @throws OutOfRangeException
     */
    public function build ($format = 'bootstrap', $separator = null, $casing = null, $customizations = array())
    {
        if (in_array($format, array_keys($this->build_formats))) {
            
            // compose the namespaced name of the builder which we wanted to use
            $builder_name = '\\Noherczeg\\Breadcrumb\\Builders\\' . ucfirst($format) . 'Builder';
            
            // instantiate it
            $builder_instance = new $builder_name($this->segments, $this->base_url);
            
            // return with the results :)
            return $builder_instance->build($separator, $casing, $customizations);
        } else {
            throw new OutOfRangeException("Invalid argument($format) provided!");
        }
    }

}
