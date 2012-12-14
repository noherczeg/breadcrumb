<?php namespace Noherczeg\Breadcrumb\Builders;

class BootstrapBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        $this->segments = $segments;
        $this->base_url = $base_url;
    }

    /**
     * build: The builder method which creates Bootsrap style breadcrumbs
     * 
     * @param String|null $separator
     * @param String|null $casing
     * @param array $customizations
     * @return type
     */
    public function build ($separator = null, $casing = null, $customizations = array())
    {
        (is_null($separator))   ? $ts = $this->config->value('separator')      : $ts = $separator;
        (is_null($casing))      ? $tc = $this->config->value('default_casing') : $tc = $casing;

        $result = '<ul class="breadcrumb">';

        foreach ($this->segments as $key => $segment) {
            if ($key == $last_key) {
                $result .= '<li class="active">' . $segment->get('raw') . '</li>';
            } else {
                $result .= '<li><a href="' . $segment->get('link') . '" ' . $this->customize($customizations) . '>' . $this->casing($casing, $segment->get('translated')) . '</a> <span class="divider">' . $ts . '</span></li>';
            }
        }

        return $result . '</ul>';
    }
}
