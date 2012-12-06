<?php

class SegmentTest extends PHPUnit_Framework_TestCase
{
	private $segment = null;

	/**
	 * Setup the test enviroment
	 */
	 public function setUp()
	 {
	 	$this->segment = new Noherczeg\Breadcrumb\Segment('test');
	 }

	 /**
	  * Teardown the test enviroment
	  */
	public function tearDown()
	{
		$this->segment = null;
	}

	/**
	 * Test instance of $this->segment
	 * @test
	 */
	public function testInstanceOf()
	{
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $this->segment);
	}
}