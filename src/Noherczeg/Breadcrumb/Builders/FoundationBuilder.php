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
     * WARNING! Foundation won't accept any separator, or properties submitted
     * to it, since it's not in the model by default! Parameter is only there as
     * placeholder!
     *
     * @param String|null $casing       Casing option
     * @param boolean $last_not_link    True if last shouldn't be a link
     * @return String
     */
    public function build ($casing = null, $last_not_link = true,  $separator = null, $properties = null)
    {
        // always create link on build stage!
        $this->link($last_not_link);

        // handle default
        (is_null($casing))      ? $tc = $this->config->value('casing') : $tc = $casing;

        $result = '<ul class="breadcrumbs">';

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
