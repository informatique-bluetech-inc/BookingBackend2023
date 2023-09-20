<?php

namespace Config;

class Config
{
    static public function data()
    {
        return [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0001259855.Prod.apple.com.cert.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "VE?X*0Crkd7>sfPF@@S.",
            "REST_ACCOUNT_ID" => "bluetech-apple@infotechcorporation.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            "REST_AUTH_TOKEN_APPLE" => "42d562c0-b7d5-49fc-9173-77a36f64d68a",
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0000023532",
            "REST_ShipTo" => "0000023532",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "infotechcorp"
        ];
    }

}
