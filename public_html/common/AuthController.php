<?php
require_once __DIR__ . "/StoreAppleInfo.php";
require_once __DIR__ . "/AccessData.php";


class AuthController {



    /** 
    * This method validates with apple api if a store certificate is valid
    */
    public function checkCertificates($storeName): array {

        $messageLog = array();

        $storeAppleInfoService = new StoreAppleInfo();

        $clazzMethod = "AuthController.check";
        $messageLog[] = "Started ".$clazzMethod. " with parameters ".$storeName;
        
        $storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);
        $messageLog[] = "This is the store info from database file ".json_encode($storeInfo);

        $url = "https://api-partner-connect.apple.com/api/authenticate/check";
        $messageLog[] =  "This is the apple api url ".$url;

        $requestHeaders = array(
            'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
            'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
        );

        $messageLog[] = "This is the header for request validate token ".json_encode($requestHeaders);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $storeInfo["REST_CERT_PATH"]);
        curl_setopt($ch, CURLOPT_SSLKEY, $storeInfo["REST_SSL_KEY"]);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $storeInfo["REST_CERT_PASS"]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);

        $result = curl_exec($ch);

        if($result === false){
            $messageLog[] = "This is error trying to consume apple api = ". json_encode(curl_error($ch));
            http_response_code(500);
            return [ "status" => 500, "response" => "Error trying to consume apple api", "log"=> $messageLog];
        }

        $messageLog[] = "This is response from apple = ". json_encode($result);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if(! ($this->isResponse2xx($statusCode)) ){//if apple response is not ok
            $messageLog[] = "This is error from apple api = ". json_encode(curl_error($ch));
            http_response_code($statusCode);
            return [ "status" => $statusCode, "response" => "Error from apple api ", "log"=> $messageLog];
        }

        http_response_code($statusCode);
        return [ "status" => $statusCode, "response" => $result, "log"=> $messageLog ];
    }


    /** 
    * This method insert a token manually, for the first time
    */
    public function updateTokenManually($storeName, $body): array {
        
        if( !(isset($body->newToken))  ){
            http_response_code(400);
            return [ "status" => 400, "response" => "There is no token" ];
        }

        $database = new AccessData();
        $clazzMethod = "AuthController.updateTokenManually";
        $messageLog = array();
        $messageLog[] = "Started ".$clazzMethod. " with parameters ".$storeName;

        
        $now = date("Y-m-d H:i:s");

        $sql = "update store_tokens set token = '".$body->newToken."' , token_updated_at = '".$now."' 
        WHERE store = ".$storeName." ;";

        $messageLog[] = "Sql = ".$sql;

        if($database->executeQueryOperation($sql) == false){
            http_response_code(500);
            return [ "status" => 500, "response" => "Error updating new token in database",
                "log" => $messageLog ];
        }

        if($database->quantityRowsAffected < 1){
            $messageLog[] = "quantityRowsAffected = ".$database->quantityRowsAffected;
            http_response_code(500);
            return [ "status" => 500, "response" => "No records were affected", "log" => $messageLog ];
        }

        return ["status"=>200, "reponse"=>"Token was updated"];
    }


    /** 
    * this method validate with apple api if a store token is valid
    */
    public function refreshToken($storeName): array {

        $clazzMethod = "AuthController.refreshToken";
        $messageLog = array();
        $messageLog[] = "Started ".$clazzMethod. " with parameters ".$storeName;


        $database = new AccessData();
        $sql = "select id, token, token_updated_at from store_tokens 
        where store = '$storeName' limit 1";
        $messageLog[] = "Sql = ".$sql;

        if($database->retrieveData($sql) == false){
            $messageLog[] = "Error message  ".$database->errorMessage;
            http_response_code(500);
            return [ "status" => 500, "response" => "Error getting data from database", "log" => $messageLog ];
        }

        if(count($database->retrievedRecords) < 1){
            http_response_code(500);
            return [ "status" => 500, "response" => "There is no token saved for the store", "log" => $messageLog ];
        }

        $messageLog[] = "Data retrieved  = ".json_encode($database->retrievedRecords[0]);
        $storedTokenUpdatedAt = $database->retrievedRecords[0]["token_updated_at"];
        $storedToken = $database->retrievedRecords[0]["token"];
        $storedTokenId = $database->retrievedRecords[0]["id"];
        

        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $minutes_diff = strtotime($storedTokenUpdatedAt);
        $minutesLastUpdate = round(abs($currentDate -  $minutes_diff) / 60);
        $messageLog[] = "MinutesLastUpdate  = ".$minutesLastUpdate;

        if($minutesLastUpdate < 30){
            http_response_code(201);
            return [
                "status" => 201,
                "response" => "No need to refresh token in 30 minutes",
                "log" => $messageLog
            ];
        }

        $storeAppleInfoService = new StoreAppleInfo();
        $storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);
        $url = "https://api-partner-connect.apple.com/api/authenticate/token";
        $messageLog[] = "Apple url = ".$url;

        $requestHeaders = array(
            'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
            'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
            'X-Apple-Trace-ID: ' ,
            'X-Apple-Service-Version: v5',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Apple-Client-Locale: en-US'
        );
        $messageLog[] = "RequestHeaders  = ".json_encode($requestHeaders);

        $postData = [
            'authToken' => $storedToken,
            'userAppleId' => $storeInfo["REST_ACCOUNT_ID"]
        ];
        $messageLog[] = "PostData = ".json_encode($postData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $storeInfo["REST_CERT_PATH"]);
        curl_setopt($ch, CURLOPT_SSLKEY, $storeInfo["REST_SSL_KEY"]);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $storeInfo["REST_CERT_PASS"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        if($result === false){//if request could not be executed
            $messageLog[] = "This is error trying to consume apple api = ". json_encode(curl_error($ch));
            http_response_code(500);
            return [ "status" => 500, "response" => "Error trying to consume apple api ", "log"=> $messageLog];
        }

        $messageLog[] = "This is response from apple = ". json_encode($result);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if(! ($this->isResponse2xx($statusCode)) ){//if apple response is not ok
            $messageLog[] = "This is error from apple api = ". json_encode(curl_error($ch));
            http_response_code($statusCode);
            return [ "status" => $statusCode, "response" => "Error from apple api ", "log"=> $messageLog];
        }

        $responseApple = ($result);
        $now = date("Y-m-d H:i:s");
        
        $sql = "update store_tokens set token = '".$responseApple['authToken']."' , token_updated_at = '$now' 
        WHERE id = $storedTokenId";

        $messageLog[] = "sql = ". $sql;

        if($database->executeQueryOperation($sql) == false){
            http_response_code(500);
            return [ "status" => 500, "response" => "Error saving new token in database",
                "log" => $messageLog ];
        }

        http_response_code($statusCode);
        return [
            "status" => $statusCode,
            "response" => "New token was refreshed and saved in database",
            "log" => $messageLog
        ];

    }

    function isResponse2xx($statusCode){
        $pieces = str_split($statusCode);
        $firstElement = $pieces[0];
        
        if($firstElement == 2) return true;
        else return false;
    }
    
}
?>