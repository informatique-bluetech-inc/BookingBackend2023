<?php
header('Content-Type: application/json');
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

$storedToken = $_GET['token'];
$storeName = $_GET['store_name'];
$inputJSON = file_get_contents('php://input');
$requestBody = json_decode($inputJSON, TRUE);

$rest_sold_to = $_GET["REST_SoldTo"];
$rest_ship_to = $_GET["REST_ShipTo"];
$rest_account_id = $_GET["REST_ACCOUNT_ID"];
$rest_cert_path = $_GET["REST_CERT_PATH"];
$ssl_key = $_GET["REST_SSL_KEY"];
$cert_pass = $_GET["REST_CERT_PASS"];

$messageLog = [];
$messageLog[] = "Token " . $storedToken;
$messageLog[] = "storeName " . $storeName;

$url = "https://api-partner-connect.apple.com/api/authenticate/token";
$requestHeaders = array(
    "X-Apple-SoldTo: " . $rest_sold_to,
    "X-Apple-ShipTo: " . $rest_ship_to,
    "X-Apple-Trace-ID: " . $storedToken,
    "X-Apple-Service-Version: v5",
    "Content-Type: application/json",
    "Accept: application/json",
    "X-Apple-Client-Locale: en-US"
);
$messageLog[] = "Request headers to update token = " . json_encode($requestHeaders);
$postData = [
    "authToken" => $storedToken,
    "userAppleId" => $rest_account_id
];
$messageLog[] = "Request body to update token = " . json_encode($postData);
$messageLog[] = "Ready to execute request to update token ";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSLCERT, $rest_cert_path);
curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $cert_pass);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
if ($result === false) {
    $messageLog[] = "This is error trying to consume apple api = " . json_encode(curl_error($ch));
    http_response_code(500);
    echo json_encode(["status" => 500, "response" => "Error trying to consume apple api", "log" => $messageLog]);
    return;
}

$messageLog[] = "This is response from apple = " . ($result);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (!(isResponse2xx($statusCode))) {//if apple response is not ok
    $messageLog[] = "This is error from apple api = " . json_encode(curl_error($ch));
    http_response_code($statusCode);
    echo json_encode(["status" => $statusCode, "response" => "Error from apple api ", "log" => $messageLog]);
    return;
}
http_response_code(200);
echo json_encode(["status" => $statusCode, "response" => $result, "log" => $messageLog]);
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