<?php
include_once '../vendor/autoload.php';

use \Controllers\BotController;

$settings = include_once 'settings.php';
$botConfigs = $settings['botConfigs'];

$controller = new BotController($botConfigs);
$controller->handle();
