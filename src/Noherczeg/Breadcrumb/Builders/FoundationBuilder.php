<?php namespace Noherczeg\Breadcrumb\Builders;

class FoundationBuilder extends Builder
{

    public function __construct ($segments, $base_url)
    {
        $this->segments = $segments;
        $this->base_url = $base_url;
    }

    public function build ($separator = null, $casing = null, $customizations = array())
    {
    }
}
