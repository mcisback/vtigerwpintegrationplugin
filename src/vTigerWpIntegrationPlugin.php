<?php
namespace Mcisback\vTigerWpIntegration;

use Mcisback\WpPlugin\Base\Plugin as wpBasePlugin;
use Mcisback\WpPlugin\Helpers\ViewHelper;
use Mcisback\WpPlugin\Helpers\Settings;

if( !class_exists('vTigerWpIntegrationPlugin') ) {

    class vTigerWpIntegrationPlugin extends wpBasePlugin {
        function __construct() {
            parent::__construct();

            Settings::gI(
                SETTINGS_FILE_PATH,
                [
                    'VTIGER_BASE_URL' => 'baseUrl',
                    'VTIGER_ACCESSKEY' => 'accessKey',
                    'VTIGER_USERNAME' => 'password',
                ]
            )
                ->saveToFile()
            ;
        }

        function init() {

            $this->startSession();

            if( $this->isCurrentUserAdmin() ) {
                $this->addAction( 'admin_menu', 'buildAdminMenu');

                $this->addAction(
                    'admin_head',
                    'loadAdminScriptsAndStyles'
                );
    
                $this->loadAdminAjaxActions(
                    PLUGIN_PATH
                );
            }

            $this->loadActions(
                PLUGIN_PATH
            );
        }

        /* function loadAdminCustomScripts() {
            wp_enqueue_script( 
                PLUGIN_ID . '-admin-form.js', 
                PLUGIN_URL . '/src/views/js/admin-form.js', 
                [], 
                '1.0.0', 
                true 
            );
            wp_localize_script(
                PLUGIN_ID . '-admin-form.js',
                PLUGIN_ID . '_form',
                [
                    'url' => admin_url('admin-ajax.php'),
                    //'nonce' => wp_create_nonce(PLUGIN_ID . '_nonce')
                    'nonce' => wp_create_nonce('ajax_nonce')
                ]
            );
        } */

        function loadAdminScriptsAndStyles () {

            echo '<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>';
            echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">';
            echo '<link rel="stylesheet" href="'. PLUGIN_URL . '/src/views/css/app.css">';

        }

        function buildAdminMenu () {
            $menu = [
                'leads' => [
                    'title' => 'Leads',
                    'onMenu' => 'Leads',
                    'capabilities' => 'administrator',
                ],
            ];

            add_menu_page(
                'vTigerWp',
                'vTigerWp', // This Shows On Wordpress Menu
                'administrator',
                PLUGIN_ID, // slug
                [ $this, 'mainView' ]
            );

            foreach($menu as $slug => $menuItem) {
                add_submenu_page(
                    PLUGIN_ID, // parent slug
                    $menuItem['title'],
                    $menuItem['onMenu'], // This Shows On Wordpress Menu
                    $menuItem['capabilities'],
                    $slug, // slug
                    [ $this, 'mainView' ]
                );
            }
        }

        function mainView () {

            ViewHelper::includeWithVariables(
                PLUGIN_PATH . "/src/views/main.view.php",
                [
                    'isAdmin' => $this->isCurrentUserAdmin(),
                ],
                PRINT_OUTPUT,
                [
                ]
            );

        }
    }
}