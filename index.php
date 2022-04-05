<?php

/*

    Plugin Name: vTiger CRM Integration Plugin
    Plugin URI: http://www.marcocaggiano.com
    Description: Integrate Contact Form 7 with vTiger CRM
    Version: 1.0
    Author: Marco Caggiano
    License: Private

*/

if( ! defined( 'ABSPATH' ) ) {
    return;
}

require_once 'vendor/autoload.php';

use Mcisback\vTigerWpIntegration\vTigerWpIntegrationPlugin;

require_once './config.php';

global $wpVtigerIntegration;

$wpVtigerIntegration = new vTigerWpIntegrationPlugin();
