<?php

namespace Config;

class Config
{
    static public function data()
    {
        return [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "mudit@ishopsms.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            "REST_AUTH_TOKEN_APPLE" => "b36e3d8d-3c3b-44c8-95fd-632a2d1211fy",
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001054076",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "istore",
        ];
    }

}
