<?php
require_once __DIR__ . "/StoreAppleInfo.php";
require_once __DIR__ . "/Logger.php";
require_once __DIR__ . "/AccessData.php";


class AuthController {



    /** 
    * This method validates with apple api if a store certificate is valid
    */
    public function check($storeName): array {

        $messageLog = "";

        $logger = new Logger();
        $storeAppleInfoService = new StoreAppleInfo();

        $clazzMethod = "AuthController.check";
        $messageLog = $messageLog . "Started ".$clazzMethod. " with parameters ".$storeName."\n";
        
        $storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);
        $messageLog = $messageLog . "This is the store info from database file ".print_r($storeInfo)."\n";

        $url = $storeInfo["REST_BASE_URL"] . $storeInfo["REST_AUTH_PATH"] . "/authenticate/check";
        $messageLog = $messageLog . "This is the apple api url ".$url."\n";

        $requestHeaders = array(
            'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
            'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
        );

        $messageLog = $messageLog . "This is the header for request validate token ".print_r($requestHeaders)."\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $storeInfo["REST_CERT_PATH"]);
        curl_setopt($ch, CURLOPT_SSLKEY, $storeInfo["REST_SSL_KEY"]);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $storeInfo["REST_CERT_PASS"]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        $messageLog = $messageLog . "this is response from apple = ". print_r($result)."\n";

        if($result === false){
            http_response_code(500);
            return [ "status" => 500, "response" => "Error trying to consume apple api", "log"=> $messageLog];
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        

        http_response_code($statusCode);
        return [ "status" => $statusCode, "response" => $result, "log"=> $messageLog ];
    }



    /** 
    * this method validate with apple api if a store token is valid
    */
    public function refreshToken($storeName): array {
        $logger = new Logger();
        $clazzMethod = "AuthController.refreshToken";
        $logger->writeLog("Started ".$clazzMethod. " with parameters ".$storeName, $clazzMethod);


        $database = new AccessData();
        $sql = "select token, token_updated_at from store_tokens 
        where store = '$storeName' limit 1";
        $logger->writeLog("sql  ".$sql, $clazzMethod);

        if($database->retrieveData($sql) == false){
            http_response_code(500);
            return [ "status" => 500, "response" => "Error getting data from database" ];
        }

        $logger->writeLog("data retrieved ".$database->retrievedRecords, $clazzMethod);
        $storedTokenUpdatedAt = $database->retrievedRecords[0]["token_updated_at"];
        $storedToken = $database->retrievedRecords[0]["token"];
        $storedTokenId = $database->retrievedRecords[0]["token"];
        

        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $minutes_diff = strtotime($storedTokenUpdatedAt);
        $minutesLastUpdate = round(abs($currentDate -  $minutes_diff) / 60);

        if($minutesLastUpdate < 30){
            http_response_code(201);
            return $response = [
                "status" => 201,
                "msg" => "No need to refresh token in 30 minutes",
            ];
        }

        $storeAppleInfoService = new StoreAppleInfo();
        $storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);
        $url = $storeInfo["REST_BASE_URL"] . $storeInfo["REST_AUTH_PATH"] . "/authenticate/token";

        $request_headers = array(
            'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
            'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
            'X-Apple-Trace-ID: ' . $storeInfo["REST_AUTH_TOKEN"],
            'X-Apple-Service-Version: v5',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Apple-Client-Locale: en-US'
        );

        $postData = [
            'authToken' => $storedToken,
            'userAppleId' => $storeInfo["REST_ACCOUNT_ID"]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $storeInfo["REST_CERT_PATH"]);
        curl_setopt($ch, CURLOPT_SSLKEY, $storeInfo["REST_SSL_KEY"]);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $storeInfo["REST_CERT_PASS"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $data = json_decode($result);
        $response = [
            "status" => $statusCode,
            "response" => $data
        ];

        if($statusCode != 200 && $statusCode != 201){
            http_response_code($statusCode);
            return $response;
        }

        $newToken = $data;
        $now = date("Y-m-d H:i:s");
        
        $sql = "update store_tokens set token = '$newToken', token_updated_at = '$now' 
        WHERE id = $storedTokenId";

        if($database->executeQueryOperation($sql) == false){
            http_response_code(500);
            return [ "status" => 500, "response" => "Error setting data to database" ];
        }

        http_response_code(200);
        return [
            "status" => 200,
            "response" => "New token was saved in database"
        ];

    }
    
}
?>