<?php namespace Noherczeg\Breadcrumb\Builders;

class FoundationBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        parent::__construct($segments, $base_url);
    }

    /**
     * build: The builder method which creates Foundation style breadcrumbs
     * 
     * @param String|null $separator    Separator String
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @return String
     */
    public function build ($separator = null, $casing = null, $last_not_link = true)
    {
        // always create link on build stage!
        $this->link($last_not_link);
        
        // handle defaults
        (is_null($separator))   ? $ts = $this->config->value('separator')      : $ts = $separator;
        (is_null($casing))      ? $tc = $this->config->value('default_casing') : $tc = $casing;

        $result = '<ul class="breadcrumb">';
        
        foreach ($this->segments as $key => $segment) {
            
            if (is_null($segment->get('link'))) {
                $result .= '<li class="current"><span>' . $this->casing($segment->get('translated'), $tc) . '</span></li>';
            } else {
                $result .= '<li><a href="' . $segment->get('link') . '">' . $this->casing($segment->get('translated'), $tc) . '</a></li>';
            }
        }

        return $result . '</ul>';

    }
}
