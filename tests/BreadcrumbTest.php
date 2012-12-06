<?php

class BreadcrumbTest extends PHPUnit_Framework_TestCase
{
	private $bread = null;

	/**
	 * Setup the test enviroment
	 */
	 public function setUp()
	 {
	 	$this->bread = new Noherczeg\Breadcrumb\Breadcrumb;
	 }

	 /**
	  * Teardown the test enviroment
	  */
	public function tearDown()
	{
		$this->bread = null;
	}

	/**
	 * Test instance of $this->bread
	 *
	 * @test
	 */
	public function testInstanceOf()
	{
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Breadcrumb', $this->bread);
	}

	/**
	 * Test provide invalid argument thrown as exception
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testSetParamMethod()
	{
		$this->bread->setParam(2);
	}

}