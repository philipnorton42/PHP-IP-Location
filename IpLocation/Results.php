<?php
/**
 * This file contains the class IpLocation_Results.
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
 * This object is a storage class for presenting the results of an IP address
 * to location conversion in a consistent and extendable manner.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    http://www.hashbangcode.com/
 *
 */
class IpLocation_Results
{
    /**
     * @var array The data of the IP conversion.
     */
    private $_results = array(
        'ip'           => '',
        'country2Char' => '',
        'countryName'  => '',
    );

    /**
     * Constructor.
     *
     * @param string $ip           The IP address.
     * @param string $country2Char 2 character code for the country.
     * @param string $countryName  The name of the country.
     */
    public function IpLocation_Results($ip, $country2Char, $countryName)
    {
        $this->_results['ip']           = $ip;
        $this->_results['country2Char'] = $country2Char;
        $this->_results['countryName']  = $countryName;
    }

    /**
     * Get value.
     *
     * @param string $name The name of the value to return
     *
     * @return null|string The value if the name is present.
     */
    public function __get($name)
    {
        if (!isset($this->_results[$name])) {
            return null;
        }
        return $this->_results[$name];
    }
}