<?php

class BuilderTest extends PHPUnit_Framework_TestCase
{

    private function getCorrectSeeds()
    {
        return array(
            new Noherczeg\Breadcrumb\Segment('asdas', true),
            new Noherczeg\Breadcrumb\Segment('2343'),
            new Noherczeg\Breadcrumb\Segment('DS_ewrwr'),
        );
    }

    /**
     * Test provide InvalidArgumentException thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
	public function testIAException ()
	{
        $correct_seed = $this->getCorrectSeeds();
        
        // failing constuctors
        $fail_builder1 = new Noherczeg\Breadcrumb\Builders\BootstrapBuilder(array(), 123);
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
        
    }

    /**
     * @Test
     */
    public function testCasing()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder');
        $this->assertSame('ASD', $stub->casing('asd', 'upper'));

        $this->assertSame('asd', $stub->casing('ASD', 'lower'));
        $this->assertSame('asd', $stub->casing('AsD', 'lower'));

        $this->assertSame('Bunny', $stub->casing('BUNNY', 'title'));
        $this->assertSame('Bunny', $stub->casing('bunny', 'title'));

        $this->assertSame('Max', $stub->casing('Max'));
    }

    /**
     * @Test
     */
    public function testProperties()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder');

        $props = array('class' => 'someclass another', 'href' => 'some/uri/for/testing');
        $this->assertSame(' class="someclass another" href="some/uri/for/testing"', $stub->properties($props));
    }

    /**
     * @Test
     */
    public function testEmptyProperties()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder');

        $props = array();
        $this->assertSame('', $stub->properties($props));
    }

    /**
     * @Test
     */
    public function testLink()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder', array($this->getCorrectSeeds(), 'http://local.dev'));

        $expectedLink = 'http://local.dev/asdas';

        $res = $stub->link();
        $resSeg1 = $res[2];
        $this->assertEquals(null, $resSeg1->get('link'));
    }

    /**
     * @Test
     */
    public function testLinkSkipLast()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder', array($this->getCorrectSeeds(), 'http://local.dev'));

        $res = $stub->link();
        $resSeg2 = $res[2];
        $this->assertEquals(null, $resSeg2->get('link'));
    }

    /**
     * @Test
     */
    public function testLinkKeepLast()
    {
        /** @var \Noherczeg\Breadcrumb\Builders\Builder $stub */
        $stub = $this->getMockForAbstractClass('Noherczeg\Breadcrumb\Builders\Builder', array($this->getCorrectSeeds(), 'http://local.dev'));

        $res = $stub->link(false);
        $resSeg2 = $res[2];
        $this->assertEquals('http://local.dev/asdas/2343/DS_ewrwr', $resSeg2->get('link'));
    }

}