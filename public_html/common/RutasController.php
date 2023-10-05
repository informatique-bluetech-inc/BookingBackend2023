<?php
require_once __DIR__."/AuthController.php";
require_once __DIR__."/ReservationController.php";

class RutasController {


    public function index( $storeName ): void
    {

        $arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

        if(count(array_filter($arrayRutas)) == 1){
            if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){
                header('Content-Type: application/json; charset=utf-8');
                $response = [
                    "status" => 201,
                    "msg" => "Welcome to ".$storeName." API ",
                ];
                echo json_encode($response);
            }
        }


        if(count(array_filter($arrayRutas)) == 2){

            if(array_filter($arrayRutas)[2] == "check"){
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
                    $controller = new AuthController();
                    $result = $controller->checkCertificates($storeName);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            }

            if(array_filter($arrayRutas)[2] == "validate_token"){
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                    $controller = new AuthController();
                    $result =  $controller->refreshToken($storeName);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            }

            if(array_filter($arrayRutas)[2] == "update_token"){
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

                    $controller = new AuthController();
                    $result = $controller->updateTokenManually($storeName);
                    echo "esto no sale";
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            }

            /*if(str_contains(array_filter($arrayRutas)[2], "date-available-slots")){
                
                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET"){

                    if(isset($_GET["productCode"]) && is_string($_GET["productCode"])) {

                        $controller = new ReservationController();
                        $controller->dateAvailableSlots(
                            $storeName,
                            $_GET["productCode"]
                        );

                        //controllers\ReservationBluetechController::dateAvailableSlots($_GET["productCode"]);
                    } else {
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
                        
                        $controller = new ReservationController();
                        $controller->timeAvailableSlots(
                            $storeName,
                            $_GET["productCode"], 
                            $_GET["reservationDate"]
                        );
                        //controllers\ReservationBluetechController::timeAvailableSlots($_GET["productCode"], $_GET["reservationDate"]);
                    } else {
                        header('Content-Type: application/json; charset=utf-8');
                        $response = [
                            "status" => 401,
                            "msg" => "Enter here the query field called productCode",
                        ];

                        echo json_encode($response);
                    }
                }
            }

            if(str_contains(array_filter($arrayRutas)[2], "create-reservation")){

                if(isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
                    
                    $controller = new ReservationController();
                    $controller->create();
                    //controllers\ReservationBluetechController::create();
                }
            }*/

        }//end count arrayRutas == 2

    }//end function index()

} //end class
?>