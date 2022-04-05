<?php
namespace Mcisback\vTigerWpIntegration;

use Mcisback\WpPlugin\Base\Plugin as wpBasePlugin;
use Mcisback\WpPlugin\Helpers\ViewHelper;

if( !class_exists('vTigerWpIntegrationPlugin') ) {

    class vTigerWpIntegrationPlugin extends wpBasePlugin {
        function __construct() {
            parent::__construct();
        }

        function init() {

            $this->startSession();

            if( $this->isCurrentUserAdmin() ) {

                add_action( 'admin_menu', [ $this, 'admin_menu' ] );

                $this->addAjaxAction( "updateCustomPost" , "updateCustomPost" );

            }

            //$this->add_custom_roles();

            add_action( 'admin_head', [ $this, 'loadBootstrap' ] );

        }

        function loadBootstrap () {

            echo "<link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B\" crossorigin=\"anonymous\">";

        }

        function admin_menu () {

            add_menu_page(
                'vTigerWp',
                'vTigerWp',
                'administrator',
                'vTigerWp',
                [ $this, 'mainView' ]
            );

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

        function updateCustomPost () {

            ob_start();

            $data = base64_decode($_POST['data']);

            $data = json_decode($data);

            foreach ($data as $index => $obj) {

                $r = update_field($obj->postAcfKey, $obj->newValue, $obj->postId);

            }

            echo "Updated";

            $output = ob_get_contents();
            ob_end_clean();

            echo $output;

            wp_die();

        }
    }

}