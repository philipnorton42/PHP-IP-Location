<?php
/**
 * This file contains the class IpLocation_Service_Mysql.
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
 * Converts an IP address into a location through a MySQL database query. This
 * class uses the CSV file from webhosting.info to insert and update the
 * database table.
 *
 * @package IpLocation
 * @author  Philip Norton <philipnorton42@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version Release: 1.0 (alpha) 16/02/2010
 * @link    http://www.hashbangcode.com/
 *
 */
class IpLocation_Service_Mysql extends IpLocation_Service_Abstract
{
    /**
     * @var resource The database connection.
     */
    private $_connection;

    /**
     * @var string The name of the IP to country file for table creation and
     *             updating purposes.
     */
    protected $ipCsvFile = 'ip-to-country.csv';

    /**
     * @var array The database connection parameters. Set up on object creation.
     */
    private $_database = array(
                            'location' => '',
                            'user'     => '',
                            'password' => '',
                            'database' => '',
                        );

    /**
     * IpLocation_Service_Mysql
     *
     * @param array $options An array of database connection options.
     */
    public function IpLocation_Service_Mysql($options)
    {
        $this->_database['location'] = $options['location'];
        $this->_database['user']     = $options['user'];
        $this->_database['password'] = $options['password'];
        $this->_database['database'] = $options['database'];
    }

    /**
     * Establish a database connection.
     *
     * @return resource The database connection.
     */
    private function _databaseConnect()
    {
        if (is_null($this->_connection)) {
            $this->_connection = mysql_connect(
                $this->_database['location'], 
                $this->_database['user'], 
                $this->_database['password']
            );
            mysql_select_db($this->_database['database']);
        }
    
        return $this->_connection;
    }
    
    /**
     * Convert the IP address into a location. This function returns a
     * IpLocation_Results object containing the results. If any results are not
     * found or if any problem occured then false is returned.
     *
     * @param string $ip The ip address to lookup.
     *
     * @return boolean|IpLocation_Results The location in the form of an
     *                                    IpLocation_Results object. False
     *                                    if result is not found.
     */
    public function getIpLocation($ip)
    {
        // Convert IP address into integer
        $convertedIp = $this->convertIpToDecimal($ip);

        // Query database.
        $sql = "SELECT * FROM iptocountry 
        WHERE ipFrom <= " . $convertedIp . " 
        AND ipTo >=" . $convertedIp . ";";

        $connection = $this->_databaseConnect();
        
        $result = mysql_query($sql, $connection);
        if (($result && mysql_num_rows($result) >= 0)) {
            $tmpData = array();
            while ($row = mysql_fetch_assoc($result)) {
                return new IpLocation_Results(
                    $ip, 
                    $row['country2Char'], 
                    $row['countryName']
                );
            };
        };
        // No address found, return false        
        return false;
    }
    
    /**
     * Update the database.
     *
     * @return boolean True if database update sucessful. Otherwise false.
     */
    public function updateData()
    {
        $connection = $this->_databaseConnect();

        if (is_null($connection)) {
            return false;
        }
        
        // Delete all data from the iptocountry table.
        mysql_query("TRUNCATE TABLE iptocountry;", $connection);
        
        // Initial insert statement
        $baseInsertSql = "INSERT INTO iptocountry(ipFrom, ipTo, 
        country2Char, countryName) VALUES ";
        
        if (($handle = fopen(dirname(__FILE__) . '/data/' . $this->ipCsvFile, "r")) !== false) {
            $counter = 0;
            $sql = array();
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $sql[] = "('" . $data[0] . "','" . $data[1] . "','" 
                . $data[2] . "','" . $data[4] . "')";
                if ($counter == 10) {
                    mysql_query(
                        $baseInsertSql . implode(',', $sql) . ";", 
                        $connection
                    );                    
                    $counter = 0;
                    $sql = array();                    
                }
                ++$counter;
            }
            fclose($handle);
        }
        
        return true;
    }

    /**
     * Create the database table.
     *
     * @return boolean True if database update sucessful.
     */
    public function createTable()
    {
        $connection = $this->_databaseConnect();

        if (is_null($connection)) {
            return false;
        }

        $sql = "CREATE TABLE  `iptocountry` (
            `ipFrom` int(10) unsigned NOT NULL,
            `ipTo` int(10) unsigned NOT NULL,
            `country2Char` varchar(3) NOT NULL,
            `countryName` varchar(60) NOT NULL,
            PRIMARY KEY (`ipFrom`,`ipTo`)
        );";
        
        mysql_query($sql, $connection);

        return true;
    }
}