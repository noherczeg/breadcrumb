<?php namespace Noherczeg\Breadcrumb\Builders;

use Noherczeg\Breadcrumb\Config;
use Noherczeg\Breadcrumb\Segment;

abstract class Builder
{

    /** @var Segment[] */
    protected $segments = null;

    /** @var string */
    protected $base_url = null;

    /** @var Config */
    protected $config = null;

    /** @var string */
    protected $casing;

    /** @var string */
    protected $separator;

    public function __construct($segments, $base_url, $config = array())
    {
        if (!is_array($segments) || empty($segments)) {
            throw new \InvalidArgumentException('A not empty array of Segments is required!');
        } elseif (!is_string($base_url)) {
            throw new \InvalidArgumentException('Base URL should be a string!');
        } else {
            $this->config = (($config instanceof Config) ? $config : new Config($config));
            $this->segments = $segments;
            $this->base_url = $base_url;
        }
    }

    /**
     * link: Inserts proper URLs to each Segment which is IN THE BUILDER's list.
     *
     * @param boolean $skip_last            To create a link for the last element or not
     * @param boolean $different_links      Each segment is appended to base_url instead of the previous segment
     * @throws \InvalidArgumentException
     * @return array
     */
    public function link($skip_last = true, $different_links = false)
    {
        if (!is_bool($skip_last)) {
            throw new \InvalidArgumentException('Link method expects a boolean variable!');
        }

        // this will change eah time we step from one segment to the next
        $current_url = $this->base_url;

        // cut off a possible trailing slash just in case...
        if (substr($current_url, -1) === '/') {
            $current_url = substr($current_url, 0, -1);
        }

        // get last id
        $keys = array_keys($this->segments);
        $last_key = end($keys);

        $position = 1;

        foreach ($this->segments as $key => $segment) {
            
            // built in fail safe for multiple base elements issue
            if ($segment->is_base() && $position === 1) {
                $this->segments[$key]->setLink($current_url);
                $position++;
                continue;
            }

            // if we allow it then
            if ($key !== $last_key || !$skip_last) {
                
                // appends the current uri segment
                $current_url = $current_url . '/' . $segment->get('raw');
                
                // only if we didn't set anything before (map does :) )
                if (strlen($segment->get('link')) == 0)
                    $this->segments[$key]->setLink($current_url);
                
            }
            if ($different_links == true) {
                $current_url = $this->base_url;
            }

            $position++;
        }

        return $this->segments;
    }

    /**
     * casing: Provides casing operation to the class.
     *
     * @param String $string    String to format
     * @param String $to        Name of casing
     * @return String
     */
    public function casing ($string, $to = '')
    {
        $res = null;
        
        // Pick one! :)
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
                $res = $string;
                break;
        }
        
        return $res;
    }
    
    /**
     * properties: Transforms an array of properties to a chain of html property key + value pairs.
     * 
     * @param array $properties     Array of properties
     * @return string               Chained properties
     * @throws \InvalidArgumentException
     */
    public function properties ($properties = array())
    {
        $res = '';
        
        if (!is_array($properties)) {
            throw new \InvalidArgumentException('Expected array as input');
        } elseif (empty($properties)) {
            return $res;
        } else {
            foreach ($properties as $key => $property) {
                $res .= ' ' . $key . '="' . $property . '"';
            }
        }
        
        return $res;
    }

    abstract function build();
}
