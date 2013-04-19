<?php

class MapTest extends PHPUnit_Framework_TestCase
{
	private $map = null;

	/**
	 * Setup the test enviroment
	 */
	public function setUp ()
	{
	 	$this->map = new Noherczeg\Breadcrumb\Map(array());
	}

	/**
	 * Teardown the test enviroment
	 */
	public function tearDown ()
	{
		$this->map = null;
	}

	/**
	 * Test instance of $this->segment
	 * @test
	 */
	public function testInstanceOf ()
	{
		$this->assertInstanceOf('Noherczeg\Breadcrumb\Map', $this->map);
	}

	/**
     * Test provide invalid argument thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArg ()
    {
        
        $const_test1 = new Noherczeg\Breadcrumb\Map(true);
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Map', $const_test1);
        
        $const_test2 = new Noherczeg\Breadcrumb\Map('Whatever', 324);
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Map', $const_test2);
        
        $const_test3 = new Noherczeg\Breadcrumb\Map(546546);
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Map', $const_test3);
    }
    
    /**
     * Test workflow
     */
    public function testWorkflow ()
    {
        $input1 = array('first' => 'link/to/it', 'second' => 'another/link');
        $testO = new Noherczeg\Breadcrumb\Map($input1);
        
        $res = $testO->getSegments();
        $this->assertEquals(count($res), 2);
        
        $first = $res[0];
        $this->assertTrue(is_string(key($first)));
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Segment', $first);
    }
    
    public function testTranslationIgnore ()
    {
        $input1 = array('first' => 'link/to/it', 'second' => 'another/link');
        $testO = new Noherczeg\Breadcrumb\Map($input1);
        
        $res = $testO->getSegments();
        
        $this->assertEquals('first', $res[0]->get('translated'));
        $this->assertEquals('second', $res[1]->get('translated'));
    }
}