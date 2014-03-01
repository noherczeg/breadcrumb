<?php namespace Noherczeg\Breadcrumb\Builders;

use Noherczeg\Breadcrumb\Segment;

class BootstrapBuilder extends Builder
{
    /**
     * build: The builder method which creates Bootsrap style breadcrumbs
     * 
     * WARNING! Bootsrap won't accept any properties submitted to it, since it's
     * not in the model by default! Parameter is only there as placeholder!
     * 
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @param String|null $separator    Separator String
     * @param array $properties
     * @param boolean $different_links Each segment is appended to base_url instead of the previous segment
     * @return String
     */
    public function build ($casing = null, $last_not_link = true, $separator = null, $properties = array(), $different_links = false)
    {
        // always create link on build stage!
    	
		$this->link($last_not_link, $different_links);

        // handle defaults
        $this->separator = (is_null($separator)) ? $this->config->value('separator') : $separator;
        $this->casing = (is_null($casing)) ? $this->config->value('casing') : $casing;

        $result = '<ul class="breadcrumb">';

        foreach ($this->segments as $key => $segment) {
            $result .= $this->appendElement($key, $segment, $properties);
        }

        return $result . '</ul>';
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $tc)
	{
		return '<li class="active">' . $this->casing($segmentProperty, $tc);
	}

    private function appendElement($key, Segment $segment)
    {
        $result = '';
        // ignore separator after the last element
        if ($key > 0) {
            $result .= ' <span class="divider">' . $this->separator . '</span></li>';
        }

        if ($segment->get('disabled')) {
            $result .= $this->getInactiveElementByFieldName($segment->get('raw'), $this->casing);
        } else if (is_null($segment->get('link'))) {
            $result .= $this->getInactiveElementByFieldName($segment->get('translated'), $this->casing);
        } else {
            $result .= '<li><a href="' . $segment->get('link') . '">' . $this->casing($segment->get('translated'), $this->casing) . '</a>';
        }
        return $result;
    }
}
