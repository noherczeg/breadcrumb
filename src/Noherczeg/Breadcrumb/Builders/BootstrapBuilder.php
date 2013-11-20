<?php namespace Noherczeg\Breadcrumb\Builders;

class BootstrapBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        parent::__construct($segments, $base_url);
    }

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
        (is_null($separator))   ? $ts = $this->config->value('separator')      : $ts = $separator;
        (is_null($casing))      ? $tc = $this->config->value('casing') : $tc = $casing;

        $result = '<ul class="breadcrumb">';

        foreach ($this->segments as $key => $segment) {

            // ignore separator after the last element
            if ($key > 0) {
                $result .= ' <span class="divider">' . $ts . '</span></li>';
            }

			if ($segment->get('disabled')) {
				$result .= $this->getInactiveElementByFieldName($segment->get('raw'), $tc);
			} else if (is_null($segment->get('link'))) {
                $result .= $this->getInactiveElementByFieldName($segment->get('translated'), $tc);
            } else {
                $result .= '<li><a href="' . $segment->get('link') . '">' . $this->casing($segment->get('translated'), $tc) . '</a>';
            }
        }

        return $result . '</ul>';
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $tc)
	{
		return '<li class="active">' . $this->casing($segmentProperty, $tc);
	}
}
