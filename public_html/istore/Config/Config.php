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
            "REST_AUTH_TOKEN_APPLE" => "dbe4c917-66fe-45de-b153-8335c22f84fx",
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001054076",//35 S Van Brunt St, Englewood
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "istore",
        ];
    }

}
