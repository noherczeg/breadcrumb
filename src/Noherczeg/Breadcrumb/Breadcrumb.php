<?php namespace Noherczeg\Breadcrumb;

/**
 * Breadcrumb
 *
 * Breadcrumb handler package.
 *
 * Check https://github.com/noherczeg/breadcrumb for usage examples!
 *
 * @package     Breadcrumb
 * @version     2.0.2
 * @author      Norbert Csaba Herczeg
 * @license     MIT
 * @copyright   (c) 2012, Norbert Csaba Herczeg
 */

use InvalidArgumentException;
use OutOfRangeException;

class FileNotFoundException extends \Exception {}

class Breadcrumb
{

    private $base_url = null;
    private $segments = array();
    private $translator = null;
    private $config = null;

    // you have to expand this if you create your own builders!
    private $build_formats = null;

    public function __construct($base_url = null, $config = 'en')
    {
        $userConf = array();
    
        // Set defaults
        is_null($base_url) ? $base_url = './' : $base_url;

        // Set objet properties
        $this->base_url = $this->setParam($base_url);
        
        // backwards compatibility
        if(is_string($config)) {
            $userConf = array('language' => $config);
        } else {
            $userConf = $config;
        }
        
        // Load configurations
        $this->config = new Config($userConf);
        
        // Load Util Classes
        $this->translator = new Translator($config);

		// load builders
		$builderDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'Builders';
		$excluded = array('Builder.php', '.', '..');

		if (is_dir($builderDirectory)) {
			$handle = opendir($builderDirectory);

			if ($handle) {
				while (($entry = readdir($handle)) !== false) {
					if(!in_array($entry, $excluded)) {
						$this->build_formats[] = strtolower(substr($entry, 0, -11));
					}
				}
			} else {
				throw new \Exception('Can\'t open builder directory, check the permissions!');
			}
		} else {
			throw new \Exception('Can\'t open builder directory, maybe it doesn\t exists?');
		}
	}

    /**
     * setParam: basic system method, don't bother.
     *
     * @param mixed $to_this    String or null
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
     * @param String $raw_name      Name of the appendable Segment
     * @param String $side          Which side to place the segment in the array
     * @param boolean $base         true if it is refering to the base url
     * @param mixed $translate      Set to true if you want to use the provided dictionary, 
     *                              set to false if you want to skip translation, or
     *                              set to a specific string to assign that value
     * @return \Noherczeg\Breadcrumb\Breadcrumb
     * @throws InvalidArgumentException
     */
    public function append($raw_name = null, $side = 'right', $base = false, $translate = true)
    {
        if (!is_string($raw_name) && !is_int($raw_name) && !in_array($side, array('left', 'right'))) {
            throw new InvalidArgumentException("Wrong type of arguments provided!");
        } else {

            // create segment
            $segment = new Segment($raw_name, $base);

            // translate it, or not, by the rules we provide
            if ($translate) {
                if (is_string($translate) && strlen($translate) > 0) {
                    $segment->setTranslated($translate);
                } elseif (is_bool($translate)) {
                    $segment->setTranslated($this->translator->translate($raw_name));
                }
            } else {
                $segment->setTranslated($raw_name);
            }

            // place it in the list
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
     * remove: Removes an element from the list, optionaly can reindex the list
     * after removal.
     *
     * Supports method chaining.
     *
     * @param int $pos                          Position of the element
     * @param boolean $reindex_after_remove     To do the reindex or not
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
     * from: Reads the first parameter which can be a String, PHP array, JSON
     * array and creates + appends Segments from it in one step.
     *
     * Supports method caining.
     *
     * @param mixed $input      Either: PHP array, JSON array, URI string
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
                if (!empty($input)) {
                    $guaranteed_array = $input;
                } else {
                    throw new InvalidArgumentException("Not empty array required!");
                }

            }

            // URI string
            if (is_string($input) && json_decode($input) != null) {
                $guaranteed_array = array_values(json_decode($input));

            // JSON array
            } elseif (is_string($input)) {
                $guaranteed_array = preg_split('/\//', $input, -1, PREG_SPLIT_NO_EMPTY);
            }

            // append all
            foreach ($guaranteed_array as $segment_raw_name) {
                $this->append($segment_raw_name);
            }

            // chaining support :)
            return $this;
        }
    }
    
    /**
     * Registers a list of title => link pairs with the package.
     * 
     * All of the given data will be used as-is no translation, no URL converion
     * will be applied!
     * 
     * @param array $rawArray Array with title => link pairs
     * @return \Noherczeg\Breadcrumb\Breadcrumb
     */
    public function map(array $rawArray) {
        $map = new Map($rawArray);
        $this->segments = $map->getSegments();
        
        return $this;
    }

    /**
     * num_of_segments: Returns the number of segments which are registered
     * in the system.
     *
     * @return int
     */
    public function num_of_segments()
    {
        return count($this->segments);
    }

    /**
     * registered: Returns all the registered Segments.
     *
     * @return array
     */
    public function registered()
    {
        return $this->segments;
    }

    /**
     * segment: A getter which returns the Segment which is at the given
     * position.
     *
     * @param String $id        The ID of the required Segment.
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
     * Supports separator switching, casing switching, and custom property
     * insertion from an array (only if output is set to html!).
     *
     * @param String $format            Format of the output
     * @param String|null $casing       Casing of Segments
     * @param String|null $separator    Separator String (not there in Foundation!)
     * @param array $customizations     Array of properties (only in HTML!)
     * @return String
     * @throws OutOfRangeException
     */
    public function build ($format = null, $casing = null, $last_not_link = true, $separator = null, $customizations = array())
    {
        (is_null($format)) ? $format = $this->config->value('output_format') : $format = $format;

        if (in_array($format, $this->build_formats)) {

            // compose the namespaced name of the builder which we wanted to use
            $builder_name = '\\Noherczeg\\Breadcrumb\\Builders\\' . ucfirst($format) . 'Builder';

            // instantiate it
            $builder_instance = new $builder_name($this->segments, $this->base_url);

            // return with the results :)
            return $builder_instance->build($casing, $last_not_link, $separator, $customizations);
        } else {
            throw new OutOfRangeException("Provided output format($format) is not supported!");
        }
    }
}
