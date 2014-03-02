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