<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
    private $config = null;

    /**
     * Setup the test enviroment
     */
    public function setUp ()
    {
        $this->config = new Noherczeg\Breadcrumb\Config;
    }

    /**
     * Teardown the test enviroment
     */
    public function tearDown ()
    {
        $this->config = null;
    }

    /**
     * Test instance of $this->segment
     * @test
     */
    public function testInstanceOf ()
    {
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Config', $this->config);
    }

    /**
     * Test if configs are loaded properly
     *
     * @test
     */
    public function testConfigLoaded ()
    {
        $this->assertTrue(is_array($this->config->dump()));
    }

    /**
     * Test provide invalid argument thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArg ()
    {
        $this->config->value(1.23);
        $this->config->value(array('yo' => 'for shure'));
        $this->config->value(true);
    }
    
    /**
     * Test provide OutOfRangeException thrown as exception
     *
     * @expectedException OutOfRangeException
     */
    public function testOutOfRangeException ()
    {
        $this->config->value('asdfsfds');
        $this->config->value('3444543');
    }
    
    public function testBasics ()
    {
        $this->config->value('default_language');
    }
}