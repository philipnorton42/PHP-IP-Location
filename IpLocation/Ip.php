<?php
/**
 * This file contains the class IpLocation_Ip.
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
 * Include IpLocation_Results
 */
require_once 'Results.php';

/**
 * Provides encapsulation for IP to location translation.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    http://www.hashbangcode.com/
 *
 */
class IpLocation_Ip
{
    /**
     * @var string The last IP address converted.
     */
    public $ip;

    /**
     * @var IpLocation_Results The location object.
     */
    public $results;

    /**
     * @var IpLocation_Service_Abstract The location service to use when
     *                                  converting IP addresses into locations.
     */
    private $_ipLocationService;

    /**
     * IpLocation_Ip
     *
     * @param IpLocation_Service_Abstract $locationService The location service
     *                                                     to use in this lookup.
     */
    public function IpLocation_Ip(IpLocation_Service_Abstract $locationService)
    {
        $this->_ipLocationService = $locationService;
    }

    /**
     * Use the location service to lookup the IP address location and return the
     * result object.
     *
     * @param string $ip The ip address to lookup.
     *
     * @return string The location
     */
    public function getIpLocation($ip)
    {
        if ($this->validateIp($ip) === false) {
            return false;
        }

        $this->results = $this->_ipLocationService->getIpLocation($ip);
        return $this->results;
    }

    /**
     * Validate IP address
     *
     * @param string $ip The IP address
     *
     * @return boolean True if IP address is valid.
     */
    public function validateIp($ip)
    {
        if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/D', $ip)) {
            return false;
        }

        $octets = explode('.', $ip);

        for ($i = 1; $i < 5; $i++) {
            $octet = (int)$octets[($i-1)];
            if ($i === 1) {
                if ($octet > 223 OR $octet < 1) {
                    return false;
                }
            } elseif ($i === 4) {
                if ($octet < 1) {
                    return false;
                }
            } else {
                if ($octet > 254) {
                    return false;
                }
            }
        }

        return true;
    }
}