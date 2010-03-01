<?php
/**
 * This file contains the class IpLocation_Url.
 *
 * PHP Version 5.0.0
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version SVN: <svn_id>
 * @link    http://www.hashbangcode.com/
 *
 */

/**
 * Include IpLocation_Ip
 */
require_once 'Ip.php';

/**
 * Provides encapsulation for URL to location translation.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    http://www.hashbangcode.com/
 *
 */
class IpLocation_Url
{
    /**
     * @var string The last IP address converted.
     */
    public $domain;

    /**
     *
     * @var string The IP address being looke up.
     */
    public $ip;

    /**
     * @var IpLocation_Service_Abstract The location service to use when
     *                                  converting IP addresses into locations.
     */
    private $_ipLocationService;

    /**
     * IpLocation_Url
     *
     * @param IpLocation_Service_Abstract $locationService The location service 
     *                                                     to use in this lookup.
     */
    public function IpLocation_Url(IpLocation_Service_Abstract $locationService)
    {
        $this->_ipLocationService = $locationService;
    }

    /**
     * Convert a URL to a IP address.
     *
     * @param string $url The URL being converted.
     *
     * @return string The IP address converted from the URL.
     */
    public function convertDomainToIp($url)
    {
        $this->domain    = $this->getDomainNameFromUrl($url);
        $this->ip        = gethostbyname($this->domain);
        return $this->ip;
    }

    /**
     * Get just the domain name from the URL.
     *
     * @param string $url The URL to extract the domain from.
     *
     * @return string The domain name.
     */
    public function getDomainNameFromUrl($url)
    {
        $tmp    = parse_url($url);
        $domain = $tmp['host'];
        return $domain;
    }

    /**
     * Get the location from a given URL using the _ipLocationService object.
     * Returns falue if no result found or URL is invalid.
     *
     * @param string $url The URL to convert.
     *
     * @return boolean|IpLocation_Results The IpLocation_Results results
     *                                    object. Returns false if no results.
     */
    public function getUrlLocation($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            return false;
        };

        $ip = $this->convertDomainToIp($url);

        $objIpLocationObject = new IpLocation_Ip($this->_ipLocationService);
        return $objIpLocationObject->getIpLocation($ip);
    }
}