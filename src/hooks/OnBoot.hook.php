<?php
require_once 'kernel/Bouncer.php';
$bouncer = new Bouncer(ctrl_options::GetSystemOption('bouncer_config'));
$boucer->gaurd();
