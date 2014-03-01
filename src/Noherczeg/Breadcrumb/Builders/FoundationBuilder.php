<?php namespace Noherczeg\Breadcrumb\Builders;

use Noherczeg\Breadcrumb\Segment;

class FoundationBuilder extends Builder
{

    /**
     * build: The builder method which creates Foundation style breadcrumbs
     *
     * WARNING! Foundation won't accept any separator, or properties submitted
     * to it, since it's not in the model by default! Parameter is only there as
     * placeholder!
     *
     * @param String|null $casing Casing option
     * @param boolean $last_not_link True if last shouldn't be a link
     * @param null $separator
     * @param null $properties
     * @param bool $different_links
     * @return String
     */
    public function build ($casing = null, $last_not_link = true,  $separator = null, $properties = null, $different_links = false)
    {
        // always create link on build stage!
        $this->link($last_not_link, $different_links);

        // handle default
        $this->casing = (is_null($casing)) ? $this->config->value('casing') : $casing;

        $result = '<ul class="breadcrumbs">';

        foreach ($this->segments as $segment) {
			$result .= $this->appendElement($segment);
        }

        return $result . '</ul>';
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $tc, $class = 'unavailable')
	{
		return '<li class="' . $class . '"><span>' . $this->casing($segmentProperty, $tc) . '</span></li>';
	}

    private function appendElement(Segment $segment)
    {
        $result = '';
        if ($segment->get('disabled')) {
            $result .= $this->getInactiveElementByFieldName($segment->get('raw'), $this->casing);
        } else if (is_null($segment->get('link'))) {
            $result .= $this->getInactiveElementByFieldName($segment->get('translated'), $this->casing, 'current');
        } else {
            $result .= '<li><a href="' . $segment->get('link') . '">' . $this->casing($segment->get('translated'), $this->casing) . '</a></li>';
        }
        return $result;
    }
}
