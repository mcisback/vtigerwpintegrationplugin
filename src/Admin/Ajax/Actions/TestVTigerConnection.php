<?php

namespace Mcisback\vTigerWpIntegration\Admin\Ajax\Actions;

use Mcisback\WpPlugin\Base\Action as wpBaseAction;
use Mcisback\WpPlugin\Http\Response as AjaxResponse;
use Mcisback\WpPlugin\Helpers\Settings;

use Mcisback\vTiger\Api as vTigerApi;

class TestVTigerConnection extends wpBaseAction {
    protected $api = null;

    public function __construct() {
        parent::__construct(__CLASS__);

        $this->isAjax = true;
        $this->useClassNameAsActionName = true;

        $this->api = new vTigerApi(
            Settings::gi()->get('VTIGER_BASE_URL'),
            Settings::gi()->get('VTIGER_USERNAME'),
            Settings::gi()->get('VTIGER_ACCESSKEY')
        );
    }

    public function beforeRun(...$args) {
        $data = base64_decode( $_POST['data'] );

        $data = json_decode($data, true);

        $this->run(
            $data
        );
    }

    public function run(...$args) {
        $data = $args[0];

        if( !isset($this->api) || !$this->api ) {
            AjaxResponse::sendJsonFailure([
                'msg' => 'Api Is Null',
                'data' => [
                    Settings::gi()->get('VTIGER_BASE_URL'),
                    Settings::gi()->get('VTIGER_USERNAME'),
                    Settings::gi()->get('VTIGER_ACCESSKEY'),
                ],
            ]);
        }

        $validConnection = $this->api->auth()->isLogged();

        AjaxResponse::sendJson([
            'msg' => $validConnection 
                ? 
                    'Connection Valid' 
                : 
                    'Connection Invalid',
            'data' => $data,
        ], $validConnection);
    }
}