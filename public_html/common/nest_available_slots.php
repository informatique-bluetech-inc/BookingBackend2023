<?php
if (! isset($_GET['token']) ){
    echo json_encode ([ "status" => 400, "response" => "Token is required"]);
    http_response_code(400);
    return;
}
if (! isset($_GET['store_name']) ){
    echo json_encode ([ "status" => 400, "response" => "Store name is required"]);
    http_response_code(400);
    return;
}
if (! isset($_GET['product_code']) ){
    echo json_encode ([ "status" => 400, "response" => "Product code is required"]);
    http_response_code(400);
    return;
}

header('Content-Type: application/json');

$token = $_GET['token'];
$storeName = $_GET['store_name'];
$productCode = $_GET['product_code'];
$messageLog = array();

$messageLog[] = "Token parameter ".$token;
$messageLog[] = "Store name parameter ".$storeName;
$messageLog[] = "productCode parameter ".$productCode;


require_once __DIR__ . "/StoreAppleInfo.php";
$storeAppleInfoService = new StoreAppleInfo();
$storeInfo = $storeAppleInfoService->getStoreAppleInfoByStore($storeName);
$url = "https://api-partner-connect.apple.com/gsx/api/reservation/fetch-available-slots?productCode=".$productCode;


$requestHeaders = [
    'X-Apple-SoldTo: ' . $storeInfo["REST_SoldTo"],
    'X-Apple-ShipTo: ' . $storeInfo["REST_ShipTo"],
    'X-Apple-Auth-Token: ' . $token,
    'X-Apple-Trace-ID: ' . $token,
    'X-Operator-User-ID: ' . $storeInfo["REST_ACCOUNT_ID"],
    'X-Apple-Client-Timezone: America/New_York',
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
curl_setopt($ch, CURLINFO_HEADER_OUT, true); // enable tracking


$result = curl_exec($ch);
if($result === false){
    $messageLog[] = "Error trying to reach apple service. Next line is the error.";
    $messageLog[] = curl_error($ch);
    http_response_code(500);
    echo json_encode ([ "status" => 500, "response" => curl_error($ch), "log"=> $messageLog]);
    return;
}

//print_r($result);die;
$resultObj = json_decode($result);

$messageLog[] = "This is response from apple = ". ($result);

$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(! (isResponse2xx($statusCode)) ){//if apple response is not ok
    $messageLog[] = "Error returned by apple. Next line is the error.";
    $messageLog[] = $resultObj;
    echo json_encode ([ "status" => $statusCode, "response" => $resultObj, "log"=> $messageLog]);
    http_response_code($statusCode);
    return;
}

$messageLog[] = "Apple response is ok. This is the body.";
$messageLog[] = $resultObj;
http_response_code(200);
echo json_encode ([ "status" => $statusCode, "response" => $resultObj, "log"=> $messageLog ]);
return;


function isResponse2xx($statusCode){
    $statusCodeString = (string)$statusCode;
    $firstDigit = $statusCodeString[0];

    if ($firstDigit === '2') 
        return true; 
    else 
        return false;
}
?>