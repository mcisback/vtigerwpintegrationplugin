<?php
namespace Mcisback\vTigerWpIntegration;

// vTiger Config
define('VTIGER_BASE_URL', '');
define('VTIGER_USERNAME', '');
define('VTIGER_ACCESSKEY', '');

define('PLUGIN_NAME', 'vTiger Wordpress Integration');

if ( defined('ABSPATH') ) {
    define('PLUGIN_FOLDER', basename(plugin_dir_path(__FILE__)));
    define('PLUGIN_DIR', plugins_url('', __FILE__ ));
    define('PLUGIN_PATH', plugin_dir_path( __FILE__ ));
    define('PLUGIN_URL', plugins_url() . "/" . PLUGIN_FOLDER);
}
