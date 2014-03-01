<?php namespace Noherczeg\Breadcrumb\Builders;

use Noherczeg\Breadcrumb\Segment;

class RichsnippetBuilder extends Builder
{

    /** @var string */
    private $ulClass;

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
        $this->separator = (is_null($separator)) ? $this->config->value('separator') : $separator;
        $this->casing = (is_null($casing)) ? $this->config->value('casing') : $casing;
        $this->ulClass = (is_null($ul_class)) ? '' : ' class="' . $ul_class . '"';

        $result = "<ul{$this->ulClass}>";
        
        foreach ($this->segments AS $key => $segment)
		{
            $result .= $this->appendElement($key, $segment, $properties);
		}

		return $result . '</ul>';
    }
	
	private function getInactiveElementByFieldName($segmentProperty, $title)
	{
		return '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span' . $segmentProperty . ' itemprop="title">' . $title . '</span></li>';
	}

    private function appendElement($key, Segment $segment, $properties)
    {
        $result = '';
        // ignore separator after the last element
        if ($key > 0) {
            $result .= $this->separator;
        }

        if ($segment->get('disabled')) {
            $result .= $this->getInactiveElementByFieldName($this->properties($properties), $this->casing($segment->get('raw'), $this->casing));
        } elseif (is_null($segment->get('link'))) {
            $result .= $this->getInactiveElementByFieldName($this->properties($properties), $this->casing($segment->get('translated'), $this->casing));
        } else {
            $result .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $segment->get('link') . '" ' . $this->properties($properties) . ' itemprop="url">' . '<span itemprop="title">' . $this->casing($segment->get('translated'), $this->casing) . '</span>' . '</a></li>';
        }

        return $result;
    }
}
