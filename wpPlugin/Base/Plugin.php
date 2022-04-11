<?php

namespace Mcisback\WpPlugin\Base;

// use Symfony\Component\String\UnicodeString;

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

    public function addAction( 
        string $action, string $functionName, $obj=null 
    ) {
        if($obj === null) {
            $obj = $this;
        }

        add_action( $action, [ $obj, $functionName ] );

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

    public function runActionsLoader(
        string $actionsPath,
        bool $isAjax = true,
        string $actionNamespace = ''
    ) {
        $actions = glob(
            $actionsPath
        );

        if($actionNamespace === ''){
            $class_name = get_class($this);

            $reflection_class = new \ReflectionClass($class_name);

            $actionNamespace = $reflection_class->getNamespaceName();
            $actionNamespace .= $isAjax === true
                ? 
                    '\\Admin\\Ajax\\Actions'
                :
                    '\\Admin\\Actions';
        }

        //echo "CURRENT_NAMESPACE: " . $actionNamespace . "<br>";

        foreach ($actions as $actionPath) {
            $pathParts = pathinfo($actionPath);
            $className = $pathParts['filename'];

            $actionClass = "$actionNamespace\\$className";

            $actionObj = new $actionClass();

            /* 
            echo '<pre>';

            echo 'ACTION_CLASS: ' . $actionClass;
            echo '<br>';
            echo 'CLASS_NAME: ' . $className;
            echo '<br>';

            echo '</pre>';

            print_r($actionObj);
            */

            if($actionObj->useClassNameAsActionName === true) {
                $actionName = $actionObj->getClassName();
            } else {
                $actionName = $actionObj->getName();
            }

            /* echo "<pre>";
            echo "ACTION_NAME: " . $actionName;
            echo "</pre>"; */

            if($isAjax === true){

                /* echo "<pre>";
                echo "ACTION_NAME_AJAX: " . $actionName;
                echo "</pre>"; */

                $this->addAjaxAction( 
                    $actionName,
                    $actionObj->getRunFunctionName(),
                    $actionObj
                );
            } else {
                /* echo "<pre>";
                echo "ACTION_NAME_REGULAR: " . $actionName;
                echo "</pre>"; */

                $this->addAction( 
                    $actionName,
                    $actionObj->getRunFunctionName(),
                    $actionObj
                );
            }
        }
    }

    public function loadAdminAjaxActions(
        string $pluginPath,
        string $ajaxActionNamespace = ''
    ) {
        return $this->runActionsLoader(
            $pluginPath . 'src/Admin/Ajax/Actions/*.php',
            true,
            $ajaxActionNamespace
        );
    }

    public function loadActions(
        string $pluginPath,
        string $ajaxActionNamespace = ''
    ) {
        return $this->runActionsLoader(
            $pluginPath . 'src/Admin/Actions/*.php',
            false,
            $ajaxActionNamespace
        );
    }

	public abstract function init();
    //public abstract function add_custom_roles();

}
