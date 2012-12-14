<?php namespace Noherczeg\Breadcrumb\Builders;

abstract class Builder
{

    public $segments = null;
    public $base_url = null;
    public $config = null;

    public function __construct($segments, $base_url)
    {
        $this->config = new Noherczeg\Breadcrumb\Config();
        $this->segments = $segments;
        $this->base_url = $base_url;
    }

    /**
     * link: Inserts proper URLs to each Segment which is IN THE BUILDER's scope.
     * 
     * @return array
     * @throws InvalidArgumentException
     */
    public function link()
    {
        $current_url = $this->base_url;

        // cut off a possible trailing slash just in case...
        if (substr($current_url, -1) === '/') {
            $current_url = substr($current_url, 0, -1);
        }

        if (!is_array($this->segments) || empty($this->segments)) {
            throw new InvalidArgumentException('Link expects a not empty array!');
        } elseif (!is_string($this->base_url)) {
            throw new InvalidArgumentException('Base URL should be a string!');
        } else {
            $position = 1;

            foreach ($this->segments as $key => $segment) {
                if ($segment->is_base() && $position === 1) {
                    $this->segments[$key]->setLink($current_url);
                    $position++;
                    continue;
                }

                // appends the current uri segment
                $current_url = $current_url . '/' . $segment->get('raw');
                $this->segments[$key]->setLink($current_url);

                $position++;
            }
        }

        return $this->segments;
    }
    
    /**
     * casing: Provides casing operation to the class.
     * 
     * @param String $to        Name of casing
     * @param String $string    String to format
     * @return type
     */
    public function casing ($to, $string)
    {
        $res = null;
        
        switch ($to) {
            case 'lower':
                $res = mb_strtolower($string);
                break;
            case 'upper':
                $res = mb_strtoupper($string);
                break;
            case 'title':
                $res = ucwords($string);
                break;
            default:
                $res = mb_strtolower($string);
                break;
        }
        
        return $res;
    }
    
    /**
     * customize: Transforms an array of properties to a chain of html properties.
     * 
     * @param array $properties     Array of properties
     * @return string               Chained properties
     * @throws \InvalidArgumentException
     */
    public function customize ($properties = array())
    {
        $res = '';
        
        if (!is_array($properties)) {
            throw new \InvalidArgumentException('Expected array as input');
        } elseif (empty($properties)) {
            return $res;
        } else {
            foreach ($properties as $key => $value) {
                $res .= ' ' . $key . '="' . $property . '"';
            }
        }
        
        return $res;
    }

    abstract protected function build();
}
