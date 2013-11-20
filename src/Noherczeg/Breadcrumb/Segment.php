<?php namespace Noherczeg\Breadcrumb;

/**
 * Segment
 * 
 * Class that represents a single URI segment, and add functionality to it.
 * This is used and manipulated by Breadcrumb. A key building block
 */

class Segment
{

    private $raw = null;
    private $translated = null;
    private $base = false;
    private $link = null;
	private $disabled = false;

	/**
	 * Constructor
	 * 
	 * @param String	$raw_insert		The value/name of the segment in the URI
	 * @param boolean	$base			If this points to the base url of your site, then set to true, otherwise false
	 * @throws \InvalidArgumentException
	 */
    public function __construct($raw_insert, $base = false, $disabled = false)
    {
        if ((!is_string($raw_insert) && !is_int($raw_insert)) || !is_bool($base)) {
            throw new \InvalidArgumentException("Invalid arguments given, name has to be: String, optional second parameter: bool");
        } else {
            $this->raw = $raw_insert;
            $this->base = $base;
			$this->disabled = $disabled;
        }
    }

    /**
     * setTranslated: Basic setter method.
     * 
     * @param String $value
     */
    public function setTranslated($value)
    {
        $this->translated = $value;
    }

    /**
     * setLink: Basic setter method.
     * 
     * @param String $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
	
	/**
     * Disables this Segment.
	 *
	 * Basically makes the builder process ignore the link generation
	 * if it is disabled.
     */
    public function disable()
    {
        $this->disabled = true;
    }
	
	/**
     * Enables this Segment.
	 *
	 * The exact opposite of the above
     */
	public function enable()
    {
        $this->disabled = false;
    }

    /**
     * get: Mediocre getter which returns a single requested property.
     * 
     * @param String $property_name
     * @return String
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    public function get($property_name)
    {
        if (!is_string($property_name)) {
            throw new \InvalidArgumentException('Invalid parameter given!');
        } elseif (array_key_exists($property_name, get_object_vars($this))) {
            return $this->$property_name;
        } else {
            throw new \OutOfRangeException("Requested property does not exist!");
        }
    }

    /**
     * vars: alias method.
     * 
     * @return array
     */
    public function vars()
    {
        return get_object_vars($this);
    }

    /**
     * is_base: Tells if the Segment is a base Segment or not.
     * 
     * @return boolean
     */
    public function is_base()
    {
        return $this->base;
    }

}
