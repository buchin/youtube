<?php

class PracticeTest extends PHPUnit_Framework_TestCase {

    public function testHelloWorld()
    {
    	$greeting = "Hello, world";
    	$this->assertEquals($greeting, "Hello, world");
    }

    public function testLoadBuzzHttpClient()
    {
    	$browser =  new Buzz\Browser();
    	$this->assertInstanceOf('Buzz\Browser', $browser);
    }

}