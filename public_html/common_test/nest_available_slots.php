<?php
if (!isset($_GET['token'])) {
    echo json_encode(["status" => 400, "response" => "Token is required"]);
    http_response_code(400);
    return;
}
if (!isset($_GET['store_name'])) {
    echo json_encode(["status" => 400, "response" => "Store name is required"]);
    http_response_code(400);
    return;
}
if (!isset($_GET['product_code'])) {
    echo json_encode(["status" => 400, "response" => "Product code is required"]);
    http_response_code(400);
    return;
}

header('Content-Type: application/json');

$token = $_GET['token'];
$storeName = $_GET['store_name'];
$productCode = $_GET['product_code'];
$rest_sold_to = $_GET["rest_sold_to"];
$rest_ship_to = $_GET["rest_ship_to"];
$rest_account_id = $_GET["rest_account_id"];

$rest_cert_path = __DIR__ ."'"+$_GET["rest_cert_path"]+"'";
$ssl_key = __DIR__ ."'"+$_GET["rest_ssl_key"]+"'";
$cert_pass = __DIR__ ."'"+$_GET["rest_cert_pass"]+"'";

$messageLog = array();

$messageLog[] = "Token parameter " . $token;
$messageLog[] = "Store name parameter " . $storeName;
$messageLog[] = "productCode parameter " . $productCode;

$url = "https://api-partner-connect.apple.com/gsx/api/reservation/fetch-available-slots?productCode=" . $productCode;
$requestHeaders = [
    'X-Apple-SoldTo: ' . $rest_sold_to,
    'X-Apple-ShipTo: ' . $rest_ship_to,
    'X-Apple-Auth-Token: ' . $token,
    'X-Apple-Trace-ID: ' . $token,
    'X-Operator-User-ID: ' . $rest_account_id,
    'X-Apple-Client-Timezone: America/New_York',
    'X-Apple-Service-Version: v5',
    'Content-Type: application/json',
    'Accept: application/json',
    'X-Apple-Client-Locale: en-US'
];
$messageLog[] = "requestHeaders  = " . json_encode($requestHeaders);
$messageLog[] = "REST_CERT_PATH  = " . ($rest_cert_path);
$messageLog[] = "REST_SSL_KEY  = " . ($ssl_key);
$messageLog[] = "REST_CERT_PASS  = " . ($cert_pass);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSLCERT, $rest_cert_path);
curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $cert_pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true); // enable tracking

$result = curl_exec($ch);
if ($result === false) {
    $messageLog[] = "Error trying to reach apple service. Next line is the error.";
    $messageLog[] = curl_error($ch);
    http_response_code(500);
    echo json_encode(["status" => 500, "response" => curl_error($ch), "log" => $messageLog]);
    return;
}

$resultObj = json_decode($result);
$messageLog[] = "This is response from apple = " . ($result);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (!(isResponse2xx($statusCode))) {//if apple response is not ok
    $messageLog[] = "Error returned by apple. Next line is the error.";
    $messageLog[] = $result;
    echo json_encode(["status" => $statusCode, "response" => $resultObj, "log" => $messageLog]);
    http_response_code($statusCode);
    return;
}

$messageLog[] = "Apple response is ok. This is the body.";
$messageLog[] = $result;
http_response_code(200);
echo json_encode(["status" => $statusCode, "response" => $resultObj, "log" => $messageLog]);
return;

function isResponse2xx($statusCode)
{
    $statusCodeString = (string) $statusCode;
    $firstDigit = $statusCodeString[0];

    if ($firstDigit === '2')
        return true;
    else
        return false;
}
?>