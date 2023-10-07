<?php

require_once __DIR__."/../Controllers/AuthController.php";
require_once __DIR__."/../Controllers/ReservationController.php";

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if(count(array_filter($arrayRutas)) == 1){
    if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
        header('Content-Type: application/json; charset=utf-8');
        $response = [
            "status" => 201,
            "msg" => "Welcome Api iStore Booking",
        ];

        echo json_encode($response);
    }
}


if(count(array_filter($arrayRutas)) == 2){

    if(array_filter($arrayRutas)[2] == "check"){
        if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
            controllers\AuthController::check();
        }
    }

    if(array_filter($arrayRutas)[2] == "validate_token"){

        if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
           controllers\AuthController::validateToken();
        }

    }

    if(str_contains(array_filter($arrayRutas)[2], "date-available-slots")){
        if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){

            if(isset($_GET["productCode"]) && is_string($_GET["productCode"])) {
                controllers\ReservationController::dateAvailableSlots($_GET["productCode"]);
            }else{
                header('Content-Type: application/json; charset=utf-8');
                $response = [
                    "status" => 401,
                    "msg" => "Enter here the query field called productCode",
                ];

                echo json_encode($response);
            }
        }
    }


    if(str_contains(array_filter($arrayRutas)[2], "time-available-slots")){
        if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){

            if(isset($_GET["productCode"]) && is_string($_GET["productCode"])) {
                controllers\ReservationController::timeAvailableSlots($_GET["productCode"], $_GET["reservationDate"]);
            }else{
                header('Content-Type: application/json; charset=utf-8');
                $response = [
                    "status" => 401,
                    "msg" => "Enter here the query field called productCode",
                ];

                echo json_encode($response);
            }
        }
    }



    if(array_filter($arrayRutas)[2] == "create-reservation"){

        if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
           controllers\ReservationController::create();
        }

    }


}

