<?php

/**
 * Bouncer - A SentoraCP module for only allowing or disallowing control panel login access to certain IP addresses. 
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-bouncer-module
 * @license https://github.com/supared/sentora-bouncer-module/blob/master/LICENSE
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
        if ($this->getBouncerEnabled() && $this->isClientDenied($_SERVER['REMOTE_ADDR'])) {
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
        if ($this->getWhitelistEnabled()) {
            return !$this->addressIsWhitelisted($address);
        }
        if ($this->getBlacklistEnabled()) {
            return $this->addressIsBlacklisted($address);
        }
    }

    /**
     * Checks that the provided IP address is whitelisted.
     * @param string $address
     * @return boolean
     */
    private function addressIsWhitelisted($address)
    {
        if (in_array($address, $this->getWhiteistAddresses())) {
            return true;
        }
        return false;
    }

    /**
     * Checks that the provided IP address is blacklisted.
     * @param string $address
     * @return boolean
     */
    private function addressIsBlacklisted($address)
    {
        if (in_array($address, $this->getBlackistAddresses())) {
            return true;
        }
        return false;
    }

    /**
     * Check to see Bouncer currently enforcing?
     * @return boolean
     */
    public function getBouncerEnabled()
    {
        return $this->enforcing;
    }

    /**
     * Check to see if the whitelist is enabled (Blacklist will therefore be disabled)
     * @return boolean
     */
    public function getWhitelistEnabled()
    {
        return $this->whitelist_enabled;
    }

    /**
     * Check to see if the blacklist is enabled (If whitelist is enabled this is disabled)
     * @return boolean
     */
    public function getBlacklistEnabled()
    {
        return $this->blacklist_enabled;
    }

    /**
     * Return the list of IP addresses that are on the white-list (allows to access)
     * @return array
     */
    public function getWhiteistAddresses()
    {
        return $this->whitelist_addresses;
    }

    /**
     * Return the list of IP addresses that are on the blacklist (denied access)
     * @return array
     */
    public function getBlackistAddresses()
    {
        return $this->blacklist_addresses;
    }
}
