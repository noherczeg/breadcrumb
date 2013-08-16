<?php namespace Noherczeg\Breadcrumb\Builders;

class RichsnippetBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        parent::__construct($segments, $base_url);
    }

    /**
     * build: The builder method which creates rich snippet style breadcrumbs
     * https://support.google.com/webmasters/answer/185417?hl=en
     * 
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @param String|null $separator    Separator String
     * @param array $customizations     Array of properties + values
     * @param String|null $ul_class     Class of <ul> element
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
            
			if (is_null($segment->get('link'))) {
				$result .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span' . $this->properties($properties) . ' itemprop="title">' . $this->casing($segment->get('translated'), $tc) . '</span></li>';
			} else {
				$result .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $segment->get('link') . '" ' . $this->properties($properties) . ' itemprop="url">' . '<span itemprop="title">' . $this->casing($segment->get('translated'), $tc) . '</span>' . '</a></li>';
			}
		}

		return $result . '</ul>';
    }
}
