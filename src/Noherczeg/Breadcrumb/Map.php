<?php namespace Noherczeg\Breadcrumb;

/**
 * Map
 * 
 * Allows the users to create a list of custom title - link segments
 */
class Map {
    
    /** @var array The raw input */
    private $rawInput = array();
    
    /** @var array List of Segments */
    private $segments = array();
    
    /**
     * @param array $rawArray Array of raw data
     * @throws InvalidArgumentException
     */
    public function __construct($rawArray) {
        
        if (!is_array($rawArray)) {
            throw new \InvalidArgumentException("An array is required!");
        }
        
        $this->rawInput = $rawArray;
        $this->createSegmentList();
    }
    
    /**
     * @return void Just populates the list
     */
    private function createSegmentList() {
        
        foreach ($this->rawInput as $title => $link) {
            $segment = new Segment($title);
            
            $segment->setTranslated($title);
            $segment->setLink($link);
            
            $this->segments[] = $segment;
            
            $first = false;
        }
    }
    
    /**
     * @return array An array of Segments
     */
    public function getSegments() {
        return $this->segments;
    }
            
}

?>
