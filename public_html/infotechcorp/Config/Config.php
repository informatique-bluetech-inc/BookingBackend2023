<?php

namespace Config;

class Config
{
    static public function data()
    {
        return [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0000023532.Prod.apple.com.chain.pem",//change
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",//change
            "REST_CERT_PASS" => "Subject-Gawk7",//change
            "REST_ACCOUNT_ID" => "bluetech-apple@infotechcorporation.com",//change
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            "REST_AUTH_TOKEN_APPLE" => "3660f59f-76ad-47db-aee4-a27645b29bcc",//change
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0000023532",//change
            "REST_ShipTo" => "0000023532",//change
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "infotechcorp"//change
        ];
    }

}
