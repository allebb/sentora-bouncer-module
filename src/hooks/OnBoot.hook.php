<?php
/**
 * Bouncer - A SentoraCP module for only allowing or disallowing control panel login access to certain IP addresses. 
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-bouncer
 * @license https://github.com/supared/sentora-bouncer/blob/master/LICENSE
 * @version 1.0.0
 */
require_once __DIR__ . '/../kernel/Bouncer.php';
$conf = json_decode(ctrl_options::GetSystemOption('bouncer_config'), true);
$bouncer = Bouncer::getInstance($conf);
$bouncer->gaurd();
