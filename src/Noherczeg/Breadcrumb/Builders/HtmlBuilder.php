<?php namespace Noherczeg\Breadcrumb\Builders;

use Noherczeg\Breadcrumb\Segment;

class HtmlBuilder extends Builder
{

    /**
     * build: The builder method which creates HTML style breadcrumbs
     *
     * @param String|null $casing Casing option
     * @param boolean $last_not_link True if last shouldn't be a link
     * @param String|null $separator Separator String
     * @param array $properties
     * @param $different_links
     * @return String
     */
    public function build ($casing = null, $last_not_link = true, $separator = null, $properties = array(), $different_links = false)
    {
        // always create link on build stage!
        $this->link($last_not_link, $different_links);
        
        // handle defaults
        $this->separator = (is_null($separator)) ? $this->config->value('separator') : $separator;
        $this->casing = (is_null($casing)) ? $this->config->value('casing') : $casing;
        
        $result = '';
        
        foreach ($this->segments AS $key => $segment)
		{
            $result .= $this->appendElement($key, $segment, $properties);
		}

		return $result;
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $tc, $htmlProperties)
	{
		return '<span' . $htmlProperties . '>' . $this->casing($segmentProperty, $tc) . '</span>';
	}

    private function appendElement($key, Segment $segment, $properties)
    {
        $result = '';

        // ignore separator after the last element
        if ($key > 0) {
            $result .= $this->separator;
        }

        if ($segment->get('disabled')) {
            $result .= $this->getInactiveElementByFieldName($segment->get('raw'), $this->casing, $this->properties($properties));
        } else if (is_null($segment->get('link'))) {
            $result .= $this->getInactiveElementByFieldName($segment->get('translated'), $this->casing, $this->properties($properties));
        } else {
            $result .= '<a href="' . $segment->get('link') . '" ' . $this->properties($properties) . '>' . $this->casing($segment->get('translated'), $this->casing) . '</a>';
        }

        return $result;
    }
}
