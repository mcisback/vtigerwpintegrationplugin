<?php

namespace Mcisback\WpPlugin\Base;

abstract class Action {
    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function beforeRun() {
        /* Verify Nonce ?
        if ( ! wp_verify_nonce( $_POST[ PLUGIN_ID . '_nonce' ], PLUGIN_ID . '_nonce' ) ) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid Nonce'
            ]);

            wp_die();
        } */

        $this->run(
            json_decode( $_POST['data'], true )
        );
    }

    public function getRunFunctionName() {
        return 'beforeRun';
    }

    public abstract function run(array $data);
}