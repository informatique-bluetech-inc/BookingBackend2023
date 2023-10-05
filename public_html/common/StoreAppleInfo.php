<?php
class StoreAppleInfo{

    public function getStoreAppleInfoByStore($store): array {

        $storeBlueTech =
        [
            "REST_CERT_PATH" => __DIR__ . "/../bluetech/Config/AppleCare-Partner-0001259855.Prod.apple.com.cert.pem",
            "REST_SSL_KEY" =>__DIR__."/../bluetech/Config/privatekey.pem",
            "REST_CERT_PASS" => "BluetechAppleGSX2022",
            "REST_ACCOUNT_ID" => "gsxapi@ibluetech.ca",
            "REST_SoldTo" => "0001259855",
            "REST_ShipTo" => "0001259855"
        ];

        $storeiStore1 =
        [
            "REST_CERT_PATH" => __DIR__ . "/../istore/Config/AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => __DIR__ . "/../istore/Config/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001054076"
        ];

        $storeiStore2 =
        [
            "REST_CERT_PATH" => __DIR__ . "/../istore_two/Config//AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => __DIR__ . "/../istore_two/Config/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001103607",
        ];

        $storeiStore3 =
        [
            "REST_CERT_PATH" => __DIR__ . "/../istore_three/Config/AppleCare-Partner-0001054076.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => __DIR__ . "/../istore_three/Config/privatekey.pem",
            "REST_CERT_PASS" => "iStore07631Englewood",
            "REST_ACCOUNT_ID" => "service@istorestm.com",
            "REST_SoldTo" => "0001054076",
            "REST_ShipTo" => "0001221470"
        ];

        $storeInfoTechCorp =
        [
            "REST_CERT_PATH" => __DIR__ . "/../infotechcorp/Config/AppleCare-Partner-0000023532.Prod.apple.com.chain.pem",
            "REST_SSL_KEY" => __DIR__ . "/../infotechcorp/Config/privatekey.pem",
            "REST_CERT_PASS" => "Subject-Gawk7",//change
            "REST_ACCOUNT_ID" => "bluetech-apple@infotechcorporation.com",
            "REST_SoldTo" => "0000023532",
            "REST_ShipTo" => "0000023532"
        ];

        $BLUETECH = "bluetech";
        $ISTORE1 = "istore1";
        $ISTORE2 = "istore2";
        $ISTORE3 = "istore3";
        $INFOTECHCORP = "infotechcorp";

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