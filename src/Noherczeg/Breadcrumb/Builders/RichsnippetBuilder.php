<?php namespace Noherczeg\Breadcrumb\Builders;

class RichsnippetBuilder extends Builder
{
    /**
     * build: The builder method which creates rich snippet style breadcrumbs
     * https://support.google.com/webmasters/answer/185417?hl=en
     *
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @param String|null $separator    Separator String
     * @param array $properties
     * @param String|null $ul_class     Class of <ul> element
     * @param bool $different_links
     * @internal param array $customizations Array of properties + values
     * @return String
     */
    public function build ($casing = null, $last_not_link = true, $separator = null, $properties = array(), $ul_class = null, $different_links = false )
    {
        // always create link on build stage!
        $this->link($last_not_link, $different_links);
        
        // handle defaults
        (is_null($separator))   ? $ts = $this->config->value('separator')      : $ts = $separator;
        (is_null($casing))      ? $tc = $this->config->value('casing') : $tc = $casing;
        (is_null($ul_class))    ? $tu = '' : $tu = ' class="' . $ul_class . '"';

        $result = "<ul{$tu}>";
        
        foreach ($this->segments AS $key => $segment)
		{
            
            // ignore separator after the last element
            if ($key > 0) {
                $result .= $ts;
            }
            
			if ($segment->get('disabled')) {
				$result .= getInactiveElementByFieldName($this->properties($properties), $this->casing($segment->get('raw'), $tc));
			} elseif (is_null($segment->get('link'))) {
				$result .= getInactiveElementByFieldName($this->properties($properties), $this->casing($segment->get('translated'), $tc));
			} else {
				$result .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $segment->get('link') . '" ' . $this->properties($properties) . ' itemprop="url">' . '<span itemprop="title">' . $this->casing($segment->get('translated'), $tc) . '</span>' . '</a></li>';
			}
		}

		return $result . '</ul>';
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $title)
	{
		return '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span' . $segmentProperty . ' itemprop="title">' . $title . '</span></li>';;
	}
}
