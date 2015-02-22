<?php

/**
 * Bouncer - A SentoraCP module for only allowing or disallowing control panel login access to certain IP addresses. 
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-bouncer
 * @license https://github.com/supared/sentora-bouncer/blob/master/LICENSE
 * @version 1.0.0
 */
class module_controller extends ctrl_module
{

    /**
     * Template tag getter
     * @global type $controller
     * @return type
     */
    public static function getConfiguration()
    {
        global $controller;

    }

    /**
     * Form action handler
     * @global type $controller
     */
    public static function doUpdateConf()
    {
        global $controller;
        runtime_csfr::Protect();
        $formvars = $controller->GetAllControllerRequests('FORM');
        self::saveConfiguration(array(
            'enforcing' => $formvars['enabled'],
            'whitelist_enabled' => $formvars['whitelist_enabled'],
            'blacklist_enabled' => $formvars['blacklist_enabled'],
            'whitelist_addresses' => $formvars['whitelist_addresses'],
            'blacklist_addresses' => $formvars['blacklist_addresses'],
        ));
        header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=true");
        exit;
    }

    /**
     * Load the Bouncer configuration from the database.
     * @return Bouncer
     */
    private static function loadConfiguration()
    {
        require_once __DIR__ . '/../kernel/Bouncer.php';
        $conf = json_decode(ctrl_options::GetSystemOption('bouncer_config'), true);
        $bouncer = Bouncer::getInstance($conf);
        return $bouncer;
    }

    /**
     * Save the configuration to the database.
     * @return void
     */
    private static function saveConfiguration(array $options)
    {
        return ctrl_options::SetSystemOption('bouncer_config', json_encode($options), true);
    }
}
