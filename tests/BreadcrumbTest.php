<?php

class BreadcrumbTest extends PHPUnit_Framework_TestCase
{
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

}