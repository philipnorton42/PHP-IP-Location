<?php
require_once('IpLocation/Url.php');
require_once('IpLocation/Service/CsvWebhosting.php');
require_once('IpLocation/Service/GeoIp.php');

class IpLocation_UrlTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testCsvIpUrlGoogle()
    {
        $objLocationObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $results = $objLocationObject->getUrlLocation('http://www.google.com/'); // Google
        $this->assertTrue($results instanceof IpLocation_Results);
        $this->assertTrue($results->country2Char == "US");
        $this->assertTrue($results->countryName == "UNITED STATES");
    }

    public function testIpLocation_UrlGivenBoolean()
    {
        $objIpLocation_UrlObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation(true));
    }

    public function testIpLocation_UrlGivenString()
    {
        $objIpLocation_UrlObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation('asdufyt78af6tasdgj'));
    }

    public function testIpLocation_UrlGivenInteger()
    {
        $objIpLocation_UrlObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation(1278936182));
    }

    public function testIpLocation_UrlGivenFloat()
    {
        $objIpLocation_UrlObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation(1234.12345));
    }
    
    public function testIpLocation_UrlGivenInvalidUrl1()
    {
        $objIpLocation_UrlObject = new IpLocation_Url(new IpLocation_Service_CsvWebhosting());
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation("http://www.blasdasdjlasd"));
        $this->assertFalse($objIpLocation_UrlObject->getUrlLocation("www.google.com"));        
    }   
}