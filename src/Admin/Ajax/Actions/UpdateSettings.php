<?php

namespace Mcisback\vTigerWpIntegration\Admin\Ajax\Actions;

use Mcisback\WpPlugin\Base\Action as wpBaseAction;
use Mcisback\WpPlugin\Http\Response as AjaxResponse;
use Mcisback\WpPlugin\Helpers\Settings;

class UpdateSettings extends wpBaseAction {
    public function __construct() {
        parent::__construct(__CLASS__);

        $this->isAjax = true;
        $this->useClassNameAsActionName = true;
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