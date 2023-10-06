<?php
require_once __DIR__ . "/AuthController.php";
require_once __DIR__ . "/StoreAppleInfo.php";
require_once __DIR__ . "/AccessData.php";

class ReservationController{


    /**
     * 
     */
    public function getDateAvailableSlots($storeName, $deviceType): array
    {

        //create a logger variable
        $messageLog = array();
        $clazzMethod = "AuthController.getDateAvailableSlots";
        $messageLog[] = "Started ".$clazzMethod. " with parameters storeName = ".$storeName.", deviceType = ".$deviceType;

        //get the slots available
        $messageLog[] = "Calling to fetchAllAvailableSlots()";
        $resultAvailableSlots = $this->fetchAllAvailableSlots($deviceType, $storeName);
        
        
        foreach($resultAvailableSlots["log"] as $log){
            $messageLog[] = $log;
        }
        
        $messageLog[] = "Finished calling to fetchAllAvailableSlots() ";

        if(! ($this->isResponse2xx($resultAvailableSlots["status"])) ){//if apple response is not ok
            return ["status"=>$resultAvailableSlots["status"], "response"=>$resultAvailableSlots["response"], 
                "log"=>$messageLog];
        }

        $availableSlots = json_decode($resultAvailableSlots["response"], true);//decode as associative array

        $arrayTemporal = [];
        $days = [];
        $days_period = [];
        $days_unavaibles = [];

        $messageLog[] = "Working on slots = ".json_encode($availableSlots);
        foreach ($availableSlots["slots"] as $slot) {//iterate each start date and add utc format
            $arrayTemporal[] = date('Y-m-d', strtotime($slot["start"] . " UTC"));
        }

        foreach (array_unique($arrayTemporal) as $key => $value) {//delete duplicate dates
            $days[] = $value;
        }
        $messageLog[] = "Days = ".json_encode($days);

        $startDate = time();

        $period = new DatePeriod(
            new DateTime('2022-02-01'),
            //new DateTime(date('Y-m-d', strtotime('-1 day', $startDate))),
            new DateInterval('P1D'),
            //new DateTime('2021-06-30')
            new DateTime(date('Y-m-d', strtotime('+20 day', $startDate)))
        );


        foreach ($period as $key => $value) {
            $days_period[] = $value->format('Y-m-d');
        }

        foreach ($days_period as $key => $value) {
            if (!in_array($value, $days)) {
                $days_unavaibles[] = $value;
            }
        }

        http_response_code(200);
        return [
            "correlationId" => $availableSlots["correlationId"], 
            "days_unavaibles" => $days_unavaibles
        ];
    }



    /** 
     * This method gets from apple api the available slots in a year
     */
    private function fetchAllAvailableSlots($device_type, $storeName): array
    {

        //create a logger variable
        $messageLog = array();
        $clazzMethod = "AuthController.fetchAvailableSlots";
        $messageLog[] = "Started ".$clazzMethod. " with parameters ".$storeName;

        //if token is expired then get a new valid token and save it into database
        $authController = new AuthController();
        $resultFromRefreshToken = $authController->refreshToken($storeName);
        if( !  ($this->isResponse2xx($resultFromRefreshToken["status"]))  ){
            http_response_code(500);
            return [ "status" => 500, "response" => $resultFromRefreshToken["response"], "log" => $messageLog ];
        }

        //create the api apple url
        $url = "https://api-partner-connect.apple.com/gsx/api/reservation/fetch-available-slots?";
        $url = $url . "productCode=". $device_type;
        $messageLog[] = "Url api apple = ".$url;

        //create a instance of database
        $database = new AccessData();
        $sql = "select id, token, token_updated_at from store_tokens 
        where store = '$storeName' limit 1";
        $messageLog[] = "Sql = ".$sql;

        //execute query to get the token from database
        if($database->retrieveData($sql) == false){
            $messageLog[] = "Error message  ".$database->errorMessage;
            http_response_code(500);
            return [ "status" => 500, "response" => "Error getting data from database", "log" => $messageLog ];
        }
        
        //validate if got data
        if(count($database->retrievedRecords) < 1){
            http_response_code(500);
            return [ "status" => 500, "response" => "There is no token saved for the store", "log" => $messageLog ];
        }
        $messageLog[] = "Data retrieved  = ".json_encode($database->retrievedRecords[0]);
        $storedToken = $database->retrievedRecords[0]["token"];

        //get info about store certificates
        $storeAppleInfoService = new StoreAppleInfo();
        $storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);

        $requestHeaders = [
            'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
            'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
            //'X-Apple-Auth-Token: ' . $storedToken,
            'X-Apple-Service-Version: v5',
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Apple-Client-Locale: en-US'
        ];
        $messageLog[] = "requestHeaders  = ".json_encode($requestHeaders);
        $messageLog[] = "REST_CERT_PATH  = ".($storeInfo["REST_CERT_PATH"]);
        $messageLog[] = "REST_SSL_KEY  = ".($storeInfo["REST_SSL_KEY"]);
        $messageLog[] = "REST_CERT_PASS  = ".($storeInfo["REST_CERT_PASS"]);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, $storeInfo["REST_CERT_PATH"]);
        curl_setopt($ch, CURLOPT_SSLKEY, $storeInfo["REST_SSL_KEY"]);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $storeInfo["REST_CERT_PASS"]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if($result === false){
            $messageLog[] = "This is error trying to consume apple api = ". json_encode(curl_error($ch));
            http_response_code(500);
            return [ "status" => 500, "response" => "Error trying to consume apple api", "log"=> $messageLog];
        }

        $messageLog[] = "This is response from apple = ". ($result);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if(! ($this->isResponse2xx($statusCode)) ){//if apple response is not ok
            $messageLog[] = "This is error from apple api = ". json_encode(curl_error($ch));
            http_response_code($statusCode);
            return [ "status" => $statusCode, "response" => "Error from apple api ", "log"=> $messageLog];
        }

        http_response_code(200);
        return [ "status" => $statusCode, "response" => $result, "log"=> $messageLog ];
    }



    private function isResponse2xx($statusCode){
        $statusCodeString = (string)$statusCode;
        $firstDigit = $statusCodeString[0];

        if ($firstDigit === '2') 
            return true; 
        else 
            return false;
    }
}
?>