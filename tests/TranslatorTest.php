<?php

class TranslatorTest extends PHPUnit_Framework_TestCase
{
    private $tran = null;

    /**
     * Setup the test enviroment
     */
    public function setUp ()
    {
        $this->tran = new Noherczeg\Breadcrumb\Translator;
    }

    /**
     * Teardown the test enviroment
     */
    public function tearDown ()
    {
        $this->tran = null;
    }

    /**
     * Test instance of $this->segment
     * @test
     */
    public function testInstanceOf ()
    {
        $this->assertInstanceOf('Noherczeg\Breadcrumb\Translator', $this->tran);
    }

    /**
     * Test provide file not found thrown as exception
     *
     * @expectedException \Noherczeg\Breadcrumb\FileNotFoundException
     */
    public function testFileNotFoundException ()
    {
        $this->tran->loadFile(2);
        $this->tran->loadFile();
        $this->tran->loadFile(true);
        $this->tran->loadFile('asd');
    }

    /**
     * Test provide invalid argument thrown as exception
     *
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArg ()
    {
        $this->tran->loadDictionary(1.23);
        $this->tran->loadDictionary(array('yo' => 'for shure'));
        $this->tran->loadDictionary(true);

        $this->tran->translate(34);
        $this->tran->translate();
        $this->tran->translate(array('yo' => 'for shure'));
    }

    /**
     * Test if the dictionary is loaded properly
     *
     * @test
     */
    public function testCreation ()
    {
        $newInstance = new Noherczeg\Breadcrumb\Translator('en');
        $this->assertTrue(is_array($newInstance->dump()));

        $newInstance2 = new Noherczeg\Breadcrumb\Translator();
        $this->assertTrue(is_array($newInstance2->dump()));

    }
    
    public function testGeneric ()
    {
        $this->tran->loadDictionary();
    }
}