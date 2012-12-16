<?php

class SegmentTest extends PHPUnit_Framework_TestCase
{
	private $segment = null;
	private $tran = null;

	/**
	 * Setup the test enviroment
	 */
	public function setUp ()
	{
	 	$this->segment = new Noherczeg\Breadcrumb\Segment('test');
	}

	/**
	 * Teardown the test enviroment
	 */
	public function tearDown ()
	{
		$this->segment = null;
	}

	/**
	 * Test instance of $this->segment
	 * @test
	 */
	public function testInstanceOf ()
	{
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $this->segment);
	}

	/**
     * Test provide invalid argument thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArg ()
    {
        $this->segment->setTranslated(0);
        $this->segment->setTranslated(true);
        $this->segment->setTranslated(array('qwerty', '01234'));

        $this->segment->setLink(0);
        $this->segment->setLink(true);
        $this->segment->setLink(array('qwerty', '01234'));

        $this->segment->get(true);
        $this->segment->get(1);
        
        $const_test1 = new Noherczeg\Breadcrumb\Segment(true);
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $const_test1);
        
        $const_test2 = new Noherczeg\Breadcrumb\Segment('Whatever', 324);
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $const_test2);
    }

    /**
     * Test provide OutOfRangeException thrown as exception
     *
     * @expectedException OutOfRangeException
     */
    public function testOutOfRangeException ()
    {
    	$this->segment->get('id');
        $this->segment->get(true);
    }
}