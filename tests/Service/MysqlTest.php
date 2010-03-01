<?php

require_once('IpLocation/Ip.php');
require_once('IpLocation/Service/Mysql.php');

class IpLocation_Service_MySqlTest extends PHPUnit_Framework_TestCase
{
    protected $properties;

    public function setUp()
    {
        $this->properties = parse_ini_file(dirname(__FILE__) . "\properties.ini");

        $service = new IpLocation_Service_Mysql($this->properties);
        $this->assertTrue($service->createTable());
    }

    public function testUpdateData()
    {
        $service = new IpLocation_Service_Mysql($this->properties);
        $this->assertTrue($service->updateData());
    }
    
    public function testIpLocationBbc()
    {
        $objIpLocationObject = new IpLocation_Service_Mysql($this->properties);
        $results = $objIpLocationObject->getIpLocation('212.58.253.67'); // BBC
        $this->assertTrue($results instanceof IpLocation_Results);
        $this->assertTrue($results->ip == "212.58.253.67");
        $this->assertTrue($results->country2Char == "GB");
        $this->assertTrue($results->countryName == "UNITED KINGDOM");
    }

    public function testIpLocationGoogle()
    {
        $objIpLocationObject = new IpLocation_Service_Mysql($this->properties);
        $results = $objIpLocationObject->getIpLocation('66.102.9.105'); // Google
        $this->assertTrue($results instanceof IpLocation_Results);
        $this->assertTrue($results->ip == "66.102.9.105");
        $this->assertTrue($results->country2Char == "US");
        $this->assertTrue($results->countryName == "UNITED STATES");
    }

    public function testIpLocationUnknown()
    {
        $objIpLocationObject = new IpLocation_Service_Mysql($this->properties);
        $results = $objIpLocationObject->getIpLocation('127.0.0.1'); // UNKNOWN
        $this->assertFalse($results);
    }

    public function testIpLocationNonExistantIp()
    {
        $objIpLocationObject = new IpLocation_Service_Mysql($this->properties);
        $results = $objIpLocationObject->getIpLocation('10.10.10.10');
        $this->assertFalse($results);
    }
}