<?php

require_once('IpLocation/Results.php');

class IpLocation_ResultsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testNullValueReturn()
    {
        $objResultsObject = new IpLocation_Results('255.255.255.255', 'GB', 'Great Britain');
        $this->assertTrue(is_null($objResultsObject->nonexistantproperty123));
    }
}