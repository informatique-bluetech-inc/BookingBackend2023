<?php
class StoreAppleInfo{

    public function getStoreAppleInfoByStore($store): array {

        if(file_exists(__DIR__."/../bluetech/Config/AppleCare-Partner-0001259855.Prod.apple.com.cert.pem")){
            echo "el archivo no existe";
            die;
        }

        $storeBlueTech =
        [
            //"REST_CERT_PATH" => dirname(__FILE__, 1) . "/../AppleCare-Partner-0001259855.Prod.apple.com.cert.pem",
            "REST_CERT_PATH" =>realpath(__DIR__."/../bluetech/Config/AppleCare-Partner-0001259855.Prod.apple.com.cert.pem"),
            //"REST_SSL_KEY" => dirname(__FILE__, 1) . "/../privatekey.pem",
            "REST_SSL_KEY" => realpath(__DIR__."/../bluetech/Config/privatekey.pem"),
            "REST_CERT_PASS" => "BluetechAppleGSX2022",
            "REST_ACCOUNT_ID" => "gsxapi@ibluetech.ca",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            //"REST_AUTH_TOKEN_APPLE" => "70c4d580-b996-47b6-beea-b00740cb843n", get it from database
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001259855",
            "REST_ShipTo" => "0001259855",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "bluetech"
        ];

        $storeiStore1 =
        [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/../AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/../privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            //"REST_AUTH_TOKEN_APPLE" => "f036d513-63bc-4d38-88a5-fc753b23ac1p", get it from database
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001054076",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "istore",
        ];

        $storeiStore2 =
        [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            //"REST_AUTH_TOKEN_APPLE" => "f036d513-63bc-4d38-88a5-fc753b23ac1p", get it from database
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001103607",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "istore",
        ];

        $storeiStore3 =
        [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            //"REST_AUTH_TOKEN_APPLE" => "f036d513-63bc-4d38-88a5-fc753b23ac1p", get it from database
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001221470",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "istore",
        ];

        $storeInfoTechCorp =
        [
            "REST_CERT_PATH" => dirname(__FILE__, 1) . "/AppleCare-Partner-0000023532.Prod.apple.com.chain.pem",//change
            "REST_SSL_KEY" => dirname(__FILE__, 1) . "/privatekey.pem",
            "REST_CERT_PASS" => "Subject-Gawk7",//change
            "REST_ACCOUNT_ID" => "bluetech-apple@infotechcorporation.com",
            "REST_BASE_URL" => "https://api-partner-connect.apple.com",
            //"REST_AUTH_TOKEN_APPLE" => "3660f59f-76ad-47db-aee4-a27645b29bcc", get it from database
            "REST_AUTH_TOKEN" => "",
            "REST_SoldTo" => "0000023532",
            "REST_ShipTo" => "0000023532",
            "REST_AUTH_PATH" => "/api",
            "REST_GSX_PATH" => "/gsx/api",
            "REST_TABLE" => "infotechcorp"
        ];

        $BLUETECH = "BlueTech";
        $ISTORE1 = "iStore1";
        $ISTORE2 = "iStore2";
        $ISTORE3 = "iStore3";
        $INFOTECHCORP = "InfoTechCorp";

        //create array with elements as stores
        $arrayStores =
        [
            $BLUETECH => $storeBlueTech,
            $ISTORE1 => $storeiStore1,
            $ISTORE2 => $storeiStore2,
            $ISTORE3 => $storeiStore3,
            $INFOTECHCORP => $storeInfoTechCorp
        ];

        return $arrayStores[$store];

    }//end method

}//end class
?>