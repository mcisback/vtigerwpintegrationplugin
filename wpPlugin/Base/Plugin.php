<?php

namespace Mcisback\WpPlugin\Base;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

abstract class Plugin {

	protected $wpUser   = null;
	protected $wpUserMeta = null;

	function __construct() {

		add_action( 'init', [ $this, 'init' ] );

	}

	function isUserLoggedIn() {

    	return is_user_logged_in() === true || get_current_user_id() !== 0;

    }


    function isCurrentUserAdmin() {

    	if( !$this->isUserLoggedIn() ) {

    		return false;

    	}

    	return current_user_can( 'administrator' );

    }

    function addAjaxAction( string $action, string $functionName, $obj=null ) {

        if($obj === null) {
            $obj = $this;
        }

        // Action For Logged In
        add_action(
            "wp_ajax_$action",
            [ $obj, $functionName ]
        );

        // Action For Not Logged In
        add_action(
            "wp_ajax_nopriv_$action",
            [ $obj, $functionName ]
        );

    }

    public function addAction( $action, $functionName ) {

        add_action( $action, [ $this, $functionName ] );

    }

    public function redirectToPage( $pageName ) {

        wp_safe_redirect( admin_url( 'admin.php?page='.$pageName ) );

    }

    // To call in admin init hook, if you want to use sessions
    public function startSession() {

        if( !session_id() ) {

            session_start();

        }

        //var_dump( $_SESSION );

    }

    public function loadAdminAjaxActions(
        string $pluginPath,
        string $ajaxActionNamespace = ''
    ) {
        $ajaxActions = glob(
            PLUGIN_PATH . "/src/Admin/Ajax/Actions/*.php"
        );

        if($ajaxActionNamespace === ''){
            $class_name = get_class($this);

            $reflection_class = new \ReflectionClass($class_name);

            $ajaxActionNamespace = $reflection_class->getNamespaceName();
            $ajaxActionNamespace .= '\\Admin\\Ajax\\Actions';
        }

        // echo "CURRENT_NAMESPACE: " . $ajaxActionNamespace . "<br>";

        foreach ($ajaxActions as $ajaxActionPath) {
            $pathParts = pathinfo($ajaxActionPath);
            $className = $pathParts['filename'];

            $actionClass = "$ajaxActionNamespace\\$className";

            $ajaxActionObj = new $actionClass();

            /* echo 'ACTION_CLASS: ' . $actionClass;
            echo '<br>';
            echo 'CLASS_NAME: ' . $className;
            echo '<br>';

            print_r($ajaxActionObj); */

            $this->addAjaxAction( 
                $className,
                $ajaxActionObj->getRunFunctionName(),
                $ajaxActionObj
            );
        }
    }

	public abstract function init();
    //public abstract function add_custom_roles();

}
