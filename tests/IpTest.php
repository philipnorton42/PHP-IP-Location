<?php

require_once('IpLocation/Ip.php');
require_once('IpLocation/Service/CsvWebhosting.php');
require_once('IpLocation/Service/GeoIp.php');

class IpLocation_IpTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testIpLocationObject()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertTrue($objIpLocationObject instanceof IpLocation_Ip);
    }

    public function testCsvWehbostingIpLocationGoogle()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $results = $objIpLocationObject->getIpLocation('66.102.9.105'); // Google
        $this->assertTrue($results instanceof IpLocation_Results);
        $this->assertTrue($results->ip == "66.102.9.105");
        $this->assertTrue($results->country2Char == "US");
        $this->assertTrue($results->countryName == "UNITED STATES");
    }
    
    public function testCsvMaxmindIpLocationUnknown()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvMaxmind());
        $results = $objIpLocationObject->getIpLocation('127.0.0.1'); // UNKNOWN
        $this->assertFalse($results);
    }

    public function testIpLocationGivenBoolean()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation(true));
    }

    public function testIpLocationGivenString()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation('asdufyt78af6tasdgj'));
    }

    public function testIpLocationGivenInteger()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation(1278936182));
    }

    public function testIpLocationGivenFloat()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation(1234.12345));
    }
    
    public function testIpLocationGivenInvalidIp1()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation('300.300.300.300'));
    }
    
    public function testIpLocationGivenInvalidIp2()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation('3.3.0.0'));
    }
    
    public function testIpLocationGivenInvalidIp3()
    {
        $objIpLocationObject = new IpLocation_Ip(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocationObject->getIpLocation('3.300.300.30'));
    }      
}