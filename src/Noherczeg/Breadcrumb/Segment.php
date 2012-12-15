<?php namespace Noherczeg\Breadcrumb;

use InvalidArgumentException;
use OutOfRangeException;

class Segment
{

    private $raw = null;
    private $translated = null;
    private $base = false;
    private $link = null;

    public function __construct($raw_insert, $base = false)
    {
        if (!is_string($raw_insert)) {
            throw new InvalidArgumentException("Can't create segment with name provided: $raw_insert!");
        } else {
            $this->raw = $raw_insert;
            $this->base = $base;
        }
    }

    /**
     * setTranslated: Basic setter method.
     * 
     * @param String $value
     * @throws InvalidArgumentException
     */
    public function setTranslated($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Translations have to be strings!');
        } else {
            $this->translated = $value;
        }
    }

    /**
     * setLink: Basic setter method.
     * 
     * @param String $link
     * @throws InvalidArgumentException
     */
    public function setLink($link)
    {
        if (!is_string($link)) {
            throw new InvalidArgumentException('Links have to be strings!');
        } else {
            $this->link = $link;
        }
    }

    /**
     * get: Mediocre getter which returns a single requested property.
     * 
     * @param String $property_name
     * @return String
     * @throws InvalidArgumentException
     * @throws OutOfRangeException
     */
    public function get($property_name)
    {
        if (!is_string($property_name)) {
            throw new InvalidArgumentException('Invalid attempt!');
        } elseif (array_key_exists($property_name, get_object_vars($this))) {
            return $this->$property_name;
        } else {
            throw new OutOfRangeException("Invalid property requested!");
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
