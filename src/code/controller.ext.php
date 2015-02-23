<?php

/**
 * Bouncer - A SentoraCP module for only allowing or disallowing control panel login access to certain IP addresses. 
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-module
 * @license https://github.com/supared/sentora-bouncer/blob/master/LICENSE
 * @version 1.0.0
 */
class module_controller extends ctrl_module
{

    /**
     * The HTML to generate for HTML checkboxes that should be checked.
     */
    const CHECKBOX_ENABLED_HTML = "checked=\"checked\"";

    /**
     * Return the template placeholder result for the 'Bouncer enabled' checkbox.
     * @return string
     */
    public static function getIsEnabled()
    {
        if (self::bouncerConfiguration()->getBouncerEnabled()) {
            return self::CHECKBOX_ENABLED_HTML;
        }
        return null;
    }

    /**
     * Return the template placeholder result for the 'Whitelist enabled' checkbox.
     * @return string
     */
    public static function getIsWhitelistEnabled()
    {
        if (self::bouncerConfiguration()->getWhitelistEnabled()) {
            return self::CHECKBOX_ENABLED_HTML;
        }
        return null;
    }

    /**
     * Return the template placeholder result for the 'Blacklist enabled' checkbox.
     * @return string
     */
    public static function getIsBlacklistEnabled()
    {
        if (self::bouncerConfiguration()->getBlacklistEnabled()) {
            return self::CHECKBOX_ENABLED_HTML;
        }
        return null;
    }

    /**
     * Return the tempalte placeholder result for the current list of whitelist addresses.
     * @return string
     */
    public static function getWhitelistAddresses()
    {
        return implode(PHP_EOL, self::bouncerConfiguration()->getWhiteistAddresses());
    }

    /**
     * Return the tempalte placeholder result for the current list of blacklist addresses.
     * @return string
     */
    public static function getBlacklistAddresses()
    {
        return implode(PHP_EOL, self::bouncerConfiguration()->getBlackistAddresses());
    }

    /**
     * Display any notice messages for the current request.
     * @return string
     */
    public static function getResult()
    {
        if (isset($_REQUEST['saved']) && $_REQUEST['saved'] == 'true') {
            return ui_sysmessage::shout(ui_language::translate("Configuration changes have been saved successfully!"), "zannounceok");
        }
        if (isset($_REQUEST['saved']) && $_REQUEST['saved'] == 'false') {
            return ui_sysmessage::shout(ui_language::translate("An error occured and we changes to the Bouncer configuration could not be saved."), "zannounceerror");
        }
    }

    /**
     * Form action handler
     * @global type $controller
     */
    public static function doUpdateConf()
    {
        global $controller;
        runtime_csfr::Protect();
        $res_state = "false";
        $formvars = $controller->GetAllControllerRequests('FORM');
        if (self::saveConfiguration(array(
                'enforcing' => (boolean) $formvars['enabled'],
                'whitelist_enabled' => (boolean) $formvars['whitelist_enabled'],
                'blacklist_enabled' => (boolean) $formvars['blacklist_enabled'],
                'whitelist_addresses' => preg_split('/\r\n|[\r\n]/', $formvars['whitelist_addresses']),
                'blacklist_addresses' => preg_split('/\r\n|[\r\n]/', $formvars['blacklist_addresses']),
            ))) {
            $res_state = "true";
        }
        header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=" . $res_state);
        exit;
    }

    /**
     * Load the Bouncer configuration from the database.
     * @return Bouncer
     */
    private static function bouncerConfiguration()
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
        return ctrl_options::SetSystemOption('bouncer_config', json_encode($options));
    }
}
