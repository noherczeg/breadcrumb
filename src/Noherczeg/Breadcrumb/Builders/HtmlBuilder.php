<?php namespace Noherczeg\Breadcrumb\Builders;

class HtmlBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        parent::__construct($segments, $base_url);
    }

    /**
     * build: The builder method which creates HTML style breadcrumbs
     * 
     * @param String|null $separator    Separator String
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @param array $customizations     Array of properties + values
     * @return String
     */
    public function build ($separator = null, $casing = null, $last_not_link = true, $properties = array())
    {
        // always create link on build stage!
        $this->link($last_not_link);
        
        // handle defaults
        (is_null($separator))   ? $ts = $this->config->value('separator')      : $ts = $separator;
        (is_null($casing))      ? $tc = $this->config->value('default_casing') : $tc = $casing;
        
        $result = '';
        
        foreach ($this->segments AS $key => $segment)
		{
            
            // ignore separator after the last element
            if ($key > 0) {
                $result .= $ts;
            }
            
			if (is_null($segment->get('link'))) {
				$result .= $this->casing($segment->get('translated'), $tc);
			} else {
				$result .= '<a href="' . $segment->get('link') . '" ' . $this->properties($properties) . '>' . $this->casing($segment->get('translated'), $tc) . '</a>';
			}
		}

		return $result;
    }
}
