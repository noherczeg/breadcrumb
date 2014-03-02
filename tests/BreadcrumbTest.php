<?php

class BreadcrumbTest extends PHPUnit_Framework_TestCase
{

    /** @var \Noherczeg\Breadcrumb\Breadcrumb */
	private $bread = null;

	/**
	 * Setup the test enviroment
	 */
	public function setUp ()
	{
	 	$this->bread = new Noherczeg\Breadcrumb\Breadcrumb;
	}

	/**
	 * Teardown the test enviroment
	 */
	public function tearDown ()
	{
		$this->bread = null;
	}

	/**
	 * Test instance of $this->bread
	 *
	 * @test
	 */
	public function testInstanceOf ()
	{
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Breadcrumb', $this->bread);
	}

	/**
	 * Test append function
	 */
	public function testAppendAndRemove ()
	{
		// basic append
		$this->bread->append('asdasd');
		$this->assertEquals(1, $this->bread->num_of_segments());

		// append right side
		$this->bread->append('12rtztrz2', 'right');
		$this->assertEquals(2, $this->bread->num_of_segments());
        
        // append left base
		$this->bread->append('123232', 'left', true);
		$this->assertEquals(3, $this->bread->num_of_segments());
        
        $this->bread->remove(0);
        $this->assertEquals(2, $this->bread->num_of_segments());
        
        $this->bread->remove(1, true);
        $this->assertEquals(1, $this->bread->num_of_segments());

	}

    /**
     * Test provide OutOfRangeException thrown as exception
     *
     * @expectedException OutOfRangeException
     */
	public function testOORException ()
	{
		// remove with following reindex
		$this->bread->remove(0, true);
		$this->assertEquals(1, $this->bread->num_of_segments());

		// basic remove
		$this->bread->remove(0);
		$this->assertEquals(0, $this->bread->num_of_segments());

		// refer to non existent element
		$this->bread->segment(10);
	}
    
    /**
     * Test provide InvalidArgumentException thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
	public function testIAException ()
	{
        $this->bread->append(false, 'left', true);
        $this->bread->append(null);
        $this->bread->append('nasdal', true, 'asds');
        $this->bread->from(2323);
        $this->bread->from(array());
        
    }

    /**
	 * Test instance of a Segment
	 *
	 * @test
	 */
	public function testInstanceOfSegment ()
	{
		$this->bread->append('asd_asd');
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $this->bread->segment(0));
	}

    /**
     * @Test
     */
    public function testInputToArray()
    {
        $class = new \ReflectionClass('Noherczeg\Breadcrumb\Breadcrumb');
        $method = $class->getMethod('inputToArray');
        $method->setAccessible(true);
        $obj = new \Noherczeg\Breadcrumb\Breadcrumb();

        $input1 = array('one', 'two');
        $expected1 = array('one', 'two');
        $this->assertEquals($expected1, $method->invoke($obj, $input1));

        $input2 = 'one/two/three';
        $expected2 = array('one', 'two', 'three');
        $this->assertEquals($expected2, $method->invoke($obj, $input2));

        $input3 = '["two", "four"]';
        $expected3 = array('two', 'four');
        $this->assertEquals($expected3, $method->invoke($obj, $input3));
    }

    /**
     * @Test
     */
    public function testNotDisableSegment()
    {
        $this->bread->append('testelement', 'left', false, false);
        $seg = $this->bread->segment(0);
        $this->assertEquals(false, $seg->get('disabled'));
    }

    /**
     * @Test
     */
    public function testIsDisableSegment()
    {
        $this->bread->append('testelement', 'left', false, false);
        $this->bread->disable(0);
        $seg = $this->bread->segment(0);
        $this->assertEquals(true, $seg->get('disabled'));
    }

    /**
     * @Test
     */
    public function testMap()
    {
        $this->bread->map(array('first' => 'http://local.dev', 'second' => 'blaaa', 'third' => 'http://local.dev/2/4'));
        $segments = $this->bread->registered();

        $seg1 = $this->bread->segment(0);
        $seg2 = $this->bread->segment(1);
        $seg3 = $this->bread->segment(2);

        $this->assertEquals('first', $seg1->get('translated'));
        $this->assertEquals('http://local.dev', $seg1->get('link'));

        $this->assertEquals('second', $seg2->get('translated'));
        $this->assertEquals('blaaa', $seg2->get('link'));

        $this->assertEquals('third', $seg3->get('translated'));
        $this->assertEquals('http://local.dev/2/4', $seg3->get('link'));
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testRemove ()
    {
        $this->bread->append('one');
        $this->bread->append('two');
        $this->bread->append('three');

        $this->bread->remove(1);
        $this->bread->segment(1);
    }

    /**
     * @Test
     */
    public function testRemoveWithReorder ()
    {
        $this->bread->append('one');
        $this->bread->append('two');

        $this->bread->remove(0, true);
        $this->assertEquals('two', $this->bread->segment(0)->get('raw'));
    }

}