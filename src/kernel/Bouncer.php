<?php

/**
 * Bouncer - A SentoraCP module for only allowing or disallowing control panel login access to certain IP addresses. 
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-bouncer
 * @license https://github.com/supared/sentora-bouncer/blob/master/LICENSE
 * @version 1.0.0
 */
class Bouncer
{

    /**
     * Should bouncer check each request for client access rules?
     * @var boolean 
     */
    private $enforcing = true;

    /**
     * Is the whitelist enabled? (if the blacklist is also enabled, the blacklist takes priority)
     * @var boolean 
     */
    private $whitelist_enabled = false;

    /**
     * Is the blacklist enabled? 
     * @var boolean 
     */
    private $blacklist_enabled = true;

    /**
     * List of IP addresses that make up the whitelist.
     * @var array
     */
    private $whitelist_addresses = array();

    /**
     * List of IP addresses that make up the whitelist.
     * @var array
     */
    private $blacklist_addresses = array();

    /**
     * Registry pattern instance storage.
     * @var Bouncer
     */
    private static $instance = null;

    public static function getInstance($config = array())
    {
        if (self::$instance === null) {
            self::$instance = new Bouncer($config);
        }

        return self::$instance;
    }

    private function __clone()
    {
        
    }

    private function __construct($config = array())
    {
        if (!empty($config)) {
            $this->setConfigItems($config);
        }
    }

    /**
     * Lets check the connection and respond as required!
     * @return void
     */
    public function gaurd()
    {
        if ($this->enforcing && $this->isClientDenied($_SERVER['REMOTE_ADDR'])) {
            // We must stop this access!
            die('Your IP address has been denied by Sentora Bouncer!');
        }
    }

    /**
     * Set the Bouncer module configuration options.
     * @param array $config_array
     */
    private function setConfigItems($config_array = array())
    {
        foreach ($config_array as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * The main logic test to check if the client (IP address) should be allowed or not.
     * @param string $address Client IP address
     * @return boolean
     */
    private function isClientDenied($address)
    {
        if ($this->blacklist_enabled && in_array($address, $this->blacklist_addresses)) {
            return true;
        }
        if ($this->whitelist_enabled && in_array($address, $this->whitelist_addresses)) {
            return true;
        }
    }
}
