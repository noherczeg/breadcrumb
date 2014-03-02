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

    /** @var String */
    private $currentUrl;

    /** @var boolean */
    private $skipLast;

    public function __construct(array $segments = array(), $base_url = '', array $config = array())
    {
        if (!is_string($base_url))
            throw new \InvalidArgumentException('Base URL should be a string!');

        $this->config = (($config instanceof Config) ? $config : new Config($config));
        $this->segments = $segments;
        $this->base_url = $base_url;
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
        if (!is_bool($skip_last) || !is_bool($different_links))
            throw new \InvalidArgumentException('Link method expects a boolean variable!');

        $this->initCurrentUrl();
        $this->skipLast = $skip_last;
        $position = 1;

        foreach ($this->segments as $key => $segment) {
            $this->setLink($key, $segment, $position);
            $this->setNextCurrentURL($segment, $different_links);
            $position++;
        }

        return $this->segments;
    }

    private function isBaseElement (Segment $segment, $position)
    {
        if ($segment->is_base() && $position === 1)
            return true;
        return false;
    }

    private function setNextCurrentURL(Segment $segment, $different_links)
    {
        if ($different_links == true)
            $this->currentUrl = $this->base_url;
        else
            $this->currentUrl = $this->currentUrl . '/' . $segment->get('raw');
    }

    private function setLink ($key, Segment $segment, $position)
    {
        if ($this->isBaseElement($segment, $position) || $this->allowedToSetLink($key, $segment))
            $this->segments[$key]->setLink($this->currentUrl);
    }

    private function allowedToSetLink ($key, Segment $segment)
    {
        if (($key !== $this->getLastKey() || !$this->skipLast) && strlen($segment->get('link')) == 0)
            return true;
        return false;
    }

    private function initCurrentUrl ()
    {
        // this will change eah time we step from one segment to the next
        $this->currentUrl = $this->base_url;

        // cut off a possible trailing slash just in case...
        if (substr($this->currentUrl, -1) === '/')
            $this->currentUrl = substr($this->currentUrl, 0, -1);
    }

    private function getLastKey ()
    {
        $keys = array_keys($this->segments);
        return end($keys);
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
                $res = ucfirst(strtolower($string));
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
    public function properties (array $properties = array())
    {
        $res = '';
        
        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $res .= ' ' . $key . '="' . $property . '"';
            }
        }

        return $res;
    }

    abstract function build();
}
