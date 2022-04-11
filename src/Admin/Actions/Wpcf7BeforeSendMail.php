<?php
namespace Mcisback\vTigerWpIntegration\Admin\Actions;

use Mcisback\WpPlugin\Base\Action as wpBaseAction;

use Mcisback\WpPlugin\Helpers\Settings;

use Mcisback\vTiger\Api as vTigerApi;

class Wpcf7BeforeSendMail extends wpBaseAction {
    public bool $isAjax = false;
    public bool $useClassNameAsActionName = false;

    public function __construct() {
        parent::__construct('wpcf7_before_send_mail');

        $this->isAjax = false;
        $this->useClassNameAsActionName = false;

        $this->api = new vTigerApi(
            Settings::gi()->get('VTIGER_BASE_URL'),
            Settings::gi()->get('VTIGER_USERNAME'),
            Settings::gi()->get('VTIGER_ACCESSKEY')
        );
    }

    // $contact_form, &$abort, $submission
    public function run(...$args) {
        // list($contact_form, $abort, $submission) = $args;

        $submission = \WPCF7_Submission::get_instance();

        $logPath = PLUGIN_PATH . '/Logs/leads.json';

        if( !file_exists( $logPath ) ) {
            $contents = [];

            file_put_contents(
                $logPath,
                json_encode( $contents )
            );
        } else {
            $contents = json_decode(
                file_get_contents( $logPath ),
                true
            );
        }
        // print_r($args[2]);
        
        $lead = $this->proccessLead(
            $submission
        );

        $lead['assigned_user_id'] = '1';
        $lead['lead_source'] = 'ohgadget.it';
        $lead['lead_status'] = 'New';

        if( $this->api->auth()->isLogged() ) {
            $this->api->createLead( $lead );
        }

        $contents[] = $lead;

        file_put_contents(
            $logPath,
            json_encode( $contents )
        );
    }

    public function proccessLead(
        $submission
    ) {
        $formFields = [
            "Nome" => "firstname",
            "Cognome" => "lastname",
            "Nome-Cognome" => "",
            "Citta" => "city",
            "Indirizzo" => "lane",
            "Numero Civico" => "",
            "Numero" => "",
            "Cap" => "code",
            "Provincia" => "state",
            "Telefono" => "phone",
            "CosaVuoiAcquistare" => "cf_853",
            "product" => "cf_851",
            "quantity" => "cf_853",
        ];

        $lead = [
            "firstname" => "",
            "lastname" => "",
            "phone" => "",
            "leadsource" => "",
            "leadstatus" => "",
            "cf_851" => "",
            "cf_853" => "",
            "assigned_user_id" => "",
            "lane" => "",
            "city" => "",
            "state" => "",
            "code" => "",
            "country" => "Italy",
        ];

        foreach($formFields as $cField => $vField) {
            $fieldData = $submission->get_posted_data($cField);

            if($cField == "Nome-Cognome") {
                $parts = explode(' ', $fieldData);

                $lead['firstname'] = $parts[0];
                $lead['lastname'] = end($parts);
            } elseif($cField == "Indirizzo") {
                $numeroCivico = $submission->get_posted_data('Numero');

                $lead[$vField] = $fieldData . ' ' . $numeroCivico;
            }  elseif($cField == "Numero") {
                continue;
            } else {
                $lead[$vField] = $fieldData;
            }
        }

        return $lead;
    }
}