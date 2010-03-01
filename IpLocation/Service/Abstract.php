<?php
/**
 * This file contains the abstract class IpLocation_Service_Abstract.
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
 * Creates the convertIpToDecimal() function and sets the base functions needed
 * for each service.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version Release: 1.0 (alpha) 16/02/2010
 * @link    http://www.hashbangcode.com/
 *
 */
abstract class IpLocation_Service_Abstract
{
    /**
     * Convert an IP address into an integer value for data lookup.
     *
     * @param string $ip The IP address to be converted.
     *
     * @return integer The converted IP address.
     */
    protected function convertIpToDecimal($ip)
    {
        $ip = explode(".", $ip);
        return ($ip[0]*16777216) + ($ip[1]*65536) + ($ip[2]*256) + ($ip[3]);
    }

    /**
     * Lookup an IP address and return a IpLocation_Results object containing 
     * the data found.
     *
     * @param string $ip The IP address to lookup.
     *
     * @return boolean|IpLocation_Results The location in the form of an
     *                                    IpLocation_Results object. False
     *                                    if result is not found.
     */
    abstract public function getIpLocation($ip);

    /**
     * Update IP location data.
     *
     * @return boolean True if update sucessful. Otherwise false.     
     */
    abstract public function updateData();
}