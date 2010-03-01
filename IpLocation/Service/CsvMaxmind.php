<?php
/**
 * This file contains the class IpLocation_Service_CsvMaxmind.
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
 * Include IpLocation_Service_Abstract
 */
require_once 'Abstract.php';

/**
 * Converts an IP address into a location through a CSV file query. The CSV file
 * is downloaded from maxmind.com.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version Release: 1.0 (alpha) 16/02/2010
 * @link    http://www.hashbangcode.com/
 *
 */
class IpLocation_Service_CsvMaxmind extends IpLocation_Service_Abstract
{
    /**
     * The location of the data update file.
     *
     * @var string
     */
    protected $updateFile = 'http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip';
    
    /**
     * The name of the CSV file from Maxmind.
     * 
     * @var string
     */
    protected $ipCsvFile  = 'GeoIPCountryWhois.csv';
    
    /**
     * IpLocation_Service_CsvMaxmind
     */
    public function IpLocation_Service_CsvMaxmind() 
    {
    }

    /**
     * Lookup an IP address and return a IpLocation_Results object containing 
     * the data found.
     *
     * @param string $ip The ip address to lookup.
     *
     * @return IpLocation_Results|boolean An IpLocation_Results object or 
     *                                    false if no results found.
     */
    public function getIpLocation($ip) 
    {
        // Convert IP address into integer
        $convertedIp = $this->convertIpToDecimal($ip);

        if (($handle = fopen(dirname(__FILE__) . '/data/' . $this->ipCsvFile, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($convertedIp > $data[2] && $convertedIp < $data[3]) {
                    return new IpLocation_Results(
                        $ip, 
                        $data[4], 
                        strtoupper($data[5])
                    );
                }
            }
            fclose($handle);
        }
        // No address found, return false
        return false;
    }

    /**
     * Update the datafile.
     *
     * @return boolean True if file update sucessful.
     */
    public function updateData() 
    {
        $update = file_get_contents($this->updateFile);

        if (strlen($update) < 2) {
            return false;
        }

        if (!$handle = fopen('tmp.zip', 'w')) {
            return false;
        }

        if (fwrite($handle, $update) == false) {
            return false;
        }

        fclose($handle);
        
        $zip = zip_open('tmp.zip');

        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read(
                        $zip_entry, 
                        zip_entry_filesize($zip_entry)
                    );
                    $fp  = fopen(
                        dirname(__FILE__) . '/data/' . $this->ipCsvFile, 
                        'w'
                    );
                    fwrite($fp, $buf);
                    fclose($fp);
                    zip_entry_close($zip_entry);
                }
            }
            zip_close($zip);
        }

        // Delete the tmp file.
        unlink('tmp.zip');

        return true;
    }
}