<?php

namespace Mcisback\vTiger;

class Api {
    public ?string $baseUrl = null;
    public ?string $username = null;
    public ?string $accessKey = null;
    public ?string $sessionName = null;
    public $userId = null;
    public ?string $token = null;
    public $expireTime = 0;
    public bool $debug = false;

    public function __construct(
        string $baseUrl,
        string $username,
        string $accessKey,
        bool $debug = false 
    ) {
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->accessKey = $accessKey;
        $this->sessionName = null;
        $this->userId = null;
        $this->token = null;
        $this->expireTime = 0;
        $this->debug = $debug;
    }

    public function get(
        string $operation, array $queryStringData = []
    ) {
        $queryString = http_build_query(
            array_merge(
                [
                    'operation' => $operation,
                ],
                $queryStringData
            )
        );

        if($this->debug) {
            print_r(
                array_merge([
                    'method' =>'GET',
                    'url' => "{$this->baseUrl}/webservice.php",
                    'queryString' => $queryString,
                    'operation' => $operation,
                    ],
                    $queryStringData
                )
            );
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/webservice.php?$queryString",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error #: $err");
        }

        $response = json_decode($response, true);

        if($this->debug){
            echo "GET RESPONSE: \n";
            print_r($response);
            // exit(1);
        }

        if($response['success'] === false) {
            $message = $response['error']['message'];
            $code = $response['error']['code'];

            throw new \Exception("vTiger Error#$code: $message");
        }

        return $response['result'];
    }

    public function post(
        string $operation,
        array $postData = []
    ) {
        if($this->debug) {
            print_r([
                'method' =>'POST',
                'url' => "{$this->baseUrl}/webservice.php",
                'operation' => $operation,
                'postData' => $postData,
                'http_build_query' => http_build_query(
                    array_merge(
                        [
                        'operation' => $operation
                        ],
                        $postData
                    )
                ),
            ]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/webservice.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query(
                    array_merge(
                        [
                        'operation' => $operation
                        ],
                        $postData
                    )
            ),
            CURLOPT_HTTPHEADER => [
                "application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("cURL Error #: $err");
        }

        $response = json_decode($response, true);

        if($this->debug){
            echo "POST RESPONSE: \n";
            print_r($response);
            // exit(1);
        }

        if($response['success'] === false) {
            $message = $response['error']['message'];
            $code = $response['error']['code'];

            throw new \Exception("vTiger Error#$code: $message");
        }

        return $response['result'];
    }

    public function challenge() {
        $res = $this->get('getchallenge', [
            'username' => $this->username,
        ]);

        if($this->debug){
            echo "CHALLENGE RES: \n";
            print_r($res);
        }
        
        $this->token = $res['token'];
        $this->expireTime = $res['expireTime'];

        return $res;
    }

    public function login() {
        $res = $this->post('login', [
            'username' => $this->username,
            'accessKey' => md5($this->token . $this->accessKey),
        ]);

        $this->sessionName = $res['sessionName'];
        $this->userId = $res['userId'];

        return $res;
    }

    public function isLogged() {
        if($this->expireTime === 0) {
            if($this->debug){
                echo "ERROR: Expire Time is 0\n";
            }
            return false;
        }

        if(time() >= $this->expireTime) {
            if($this->debug){
                echo "ERROR: time() is greater than Expire Time\n";
                echo "Expire Time: {$this->expireTime}\n";
                echo "time(): ". time() . "\n";
            }

            return false;
        }

        if(
            $this->sessionName === null || 
            $this->userId === null ||
            $this->token === null
        ) {
            if($this->debug){
                echo "ERROR: sessionName or userId or token is null\n";
            }

            return false;
        }

        return true;
    }

    public function auth() {
        $this->challenge();
        
        $this->login();

        return $this;
    }

    public function createLead(array $fields) {
        if($this->debug) {
            echo "SENDING LEAD: \n";
            print_r([
                'username' => $this->username,
                'sessionName' => $this->sessionName,
                'elementType' => 'Leads',
                'element' => json_encode( $fields ),
                'fields' => $fields,
            ]);
            echo "\n";
        }

        return $this->post('create', [
            'username' => $this->username,
            'sessionName' => $this->sessionName,
            'elementType' => 'Leads',
            'element' => json_encode( $fields ),
        ]);
    }

    public function getUserId() {
        return $this->userId;
    }
}