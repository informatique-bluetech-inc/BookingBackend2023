<?php

namespace Config;

class ConfigBluetech
{
    static public function data()
    {
        return [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0001259855.Prod.apple.com.cert.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "BluetechAppleGSX2022",
            "REST_ACCOUNT_ID" => "gsxapi@ibluetech.ca",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            "REST_AUTH_TOKEN_APPLE" => "9b6c6d55-2f7b-477d-8490-2f36b1dcd04g",
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001259855",
            "REST_ShipTo" => "0001259855",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "bluetech"
        ];
    }

}

?>