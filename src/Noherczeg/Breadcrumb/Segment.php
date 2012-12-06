<?php namespace Noherczeg\Breadcrumb;

class SegmentException extends \Exception {}

class Segment
{
    private $raw = null;
    private $translated = null;
    private $link = null;

    public function __construct ($raw_insert)
    {
        if (!is_string($raw_insert)) {
            throw new SegmentException("Can't create segment with name provided: $raw_insert!");
        } else {
            $this->raw = $raw_insert;
        }
    }

    public function dump ()
    {
        return get_object_vars($this);
    }

}