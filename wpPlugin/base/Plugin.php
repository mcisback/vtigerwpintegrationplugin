<?php

namespace Mcisback\WpPlugin\Base;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

abstract class Plugin {

	protected $wpUser   = null;
	protected $wpUserMeta = null;

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

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

    function addAjaxAction( $action, $functionName ) {

        add_action(
            "wp_ajax_$action",
            array( $this, "$functionName" )
        );

        add_action(
            'wp_ajax_nopriv_$action',
            array( $this, "$functionName" )
        );

    }

    public function addAction( $action, $functionName ) {

        add_action( $action, array( $this, $functionName ) );

    }

    public function ajaxRespondJson( array $responseArray ) {

        echo json_encode( $responseArray );

        wp_die();

    }

    public function ajaxRespondString ( $str ) {

        echo $str;

        wp_die();

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

	public abstract function init();
    //public abstract function add_custom_roles();

}
