<?php

class BuilderTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test provide InvalidArgumentException thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
	public function testIAException ()
	{
        $correct_seed = array(
            new Noherczeg\Breadcrumb\Segment('asdas', true),
            new Noherczeg\Breadcrumb\Segment('2343'),
            new Noherczeg\Breadcrumb\Segment('DS_ewrwr'),
        );
        
        // failing constuctors
        $fail_builder1 = new Noherczeg\Breadcrumb\Builders\BootstrapBuilder(array(), 123);
        $fail_builder2 = new Noherczeg\Breadcrumb\Builders\BootstrapBuilder();
        $fail_builder3 = new Noherczeg\Breadcrumb\Builders\BootstrapBuilder('asdasda', true);
        
        // working constructor
        $correct_builer = new Noherczeg\Breadcrumb\Builders\BootstrapBuilder($correct_seed, 'http://localhost/breadcrumb');
        
        $correct_builer->link('asd');
        $correct_builer->link(1212);
        $correct_builer->link(false);
        
        $correct_builer->casing(false);
        $correct_builer->casing(2342);
        $correct_builer->casing(null);
        $correct_builer->casing(array());
        
        //$this->boostrap_builder->link(234234);
        //$this->boostrap_builder->link('qwerty');
        
    }

}