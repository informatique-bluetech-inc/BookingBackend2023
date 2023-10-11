<?php

namespace Controllers;

require_once  __DIR__."/../Models/ConfigModel.php";
require_once __DIR__."/../Models/AuthModel.php";
require_once __DIR__."/../Helpers/LogMsg.php";

use Helpers\LogMsg;
use Models\AuthModel;
use Models\ConfigModel;

class AuthBluetechController{
    protected static $state_token = 0;
    protected static function consultToken(){
        $config = new ConfigModel();
        return AuthModel::selectToken($config->REST_TABLE);
    }
    static public function check(): void {
        $config = new ConfigModel();
        $url = $config->REST_BASE_URL . $config->REST_AUTH_PATH . "/authenticate/check";

        $request_headers = array(
            'X-Apple-SoldTo: ' . $config->REST_SoldTo,
            'X-Apple-ShipTo: ' . $config->REST_ShipTo,
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $config->REST_CERT_PATH);
        curl_setopt($ch, CURLOPT_SSLKEY, $config->REST_SLL_KEY);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $config->REST_CERT_PASS);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        header('Content-Type: application/json; charset=utf-8');
        $response = [
            "status" => $statusCode,
            "response" => $result
        ];
        echo json_encode($response);
    }
    static public function validateToken(): void
    {

        $config = new ConfigModel();
        $token = self::consultToken();

        if(!$token){
           $result = AuthModel::createToken($config->REST_TABLE, $config->REST_AUTH_TOKEN_APPLE);
           if($result == "ok"){
               $config = new ConfigModel();
               $token = self::consultToken();
               self::$state_token = 1;
           }
        }

        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $last_update_auth_token =  $token['updated_at'];
        $minutes_diff = strtotime($last_update_auth_token);
        $minutes_last_update = round(abs($currentDate -  $minutes_diff) / 60);


        if($minutes_last_update >= 30 || self::$state_token == 1) {

            $url = $config->REST_BASE_URL . $config->REST_AUTH_PATH . "/authenticate/token";

            $request_headers = array(
                'X-Apple-SoldTo: ' . $config->REST_SoldTo,
                'X-Apple-ShipTo: ' . $config->REST_ShipTo,
                'X-Apple-Trace-ID: ' . $config->REST_AUTH_TOKEN,
                'X-Apple-Service-Version: v5',
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Apple-Client-Locale: en-US'
            );

            $postData = [
                'authToken' => $config->REST_AUTH_TOKEN,
                'userAppleId' => $config->REST_ACCOUNT_ID
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSLCERT, $config->REST_CERT_PATH);
            curl_setopt($ch, CURLOPT_SSLKEY, $config->REST_SLL_KEY);
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $config->REST_CERT_PASS);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


            $data = json_decode($result);

            if ($result === false) {
                LogMsg::message(curl_error($ch), 'error');
            } else {
                $response = [
                    "status" => $statusCode,
                    "response" => $data
                ];

                if($response["status"] == 201 || $response["status"] == 200 ) {
                    AuthModel::updateToken($config->REST_TABLE, $token["id"], $response["response"]);
                    LogMsg::message("Success fully update password =)");
                }

                curl_close($ch);
                LogMsg::message($data);
            }
        }else{
            $response = [
                "status" => 201,
                "msg" => "No need to refresh token in 30 minutes",
            ];

            LogMsg::message( $response, 'warning');
        }
    }


}
?>