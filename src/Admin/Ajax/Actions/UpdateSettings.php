<?php

namespace Mcisback\vTigerWpIntegration\Admin\Ajax\Actions;

use Mcisback\WpPlugin\Base\Action as wpBaseAction;
use Mcisback\WpPlugin\Http\Response as AjaxResponse;
use Mcisback\WpPlugin\Helpers\Settings;

class UpdateSettings extends wpBaseAction {
    public function __construct() {
        parent::__construct(__CLASS__);
    }

    public function beforeRun() {
        $data = base64_decode($_POST['data']);

        $data = json_decode($data, true);

        $this->run(
            $data
        );
    }

    public function run(array $data) {
        //ob_start();

        //print_r($data);

        //$output = ob_get_contents();
        //ob_end_clean();

        Settings::gI()->setSome([
            'VTIGER_BASE_URL' => $data['baseUrl'],
            'VTIGER_USERNAME' => $data['username'],
            'VTIGER_ACCESSKEY' => $data['accessKey'],
        ])
            ->saveToFile()
        ;

        AjaxResponse::sendJsonSuccess([
            'msg' => 'Settings Updated',
            'data' => $data,
        ]);
    }
}