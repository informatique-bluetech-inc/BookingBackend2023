<?php

namespace Models;

require_once __DIR__."/../Config/Config.php";

use config\Config;

class ConfigModel
{
    public $REST_CERT_PATH;
    public $REST_CERT_PASS;
    public $REST_ACCOUNT_ID;
    public $REST_BASE_URL;
    public $REST_AUTH_TOKEN_APPLE;
    public $REST_AUTH_TOKEN;
    public $REST_AUTH_PATH;
    public $REST_SoldTo;
    public $REST_ShipTo;
    public $REST_GSX_PATH;
    public $REST_SLL_KEY;
    public $REST_TABLE;

    public function __construct()
    {
        $this->REST_CERT_PATH = ConfigBluetech::data()["REST_CERT_PATH"];
        $this->REST_CERT_PASS = ConfigBluetech::data()["REST_CERT_PASS"];
        $this->REST_ACCOUNT_ID = ConfigBluetech::data()["REST_ACCOUNT_ID"];
        $this->REST_SLL_KEY = ConfigBluetech::data()["REST_SSL_KEY"];
        $this->REST_AUTH_TOKEN_APPLE = ConfigBluetech::data()["REST_AUTH_TOKEN_APPLE"];
        $this->REST_AUTH_TOKEN = ConfigBluetech::data()["REST_AUTH_TOKEN"];
        $this->REST_BASE_URL = ConfigBluetech::data()["REST_BASE_URL"];
        $this->REST_SoldTo = ConfigBluetech::data()["REST_SoldTo"];
        $this->REST_ShipTo = ConfigBluetech::data()["REST_ShipTo"];
        $this->REST_AUTH_PATH = ConfigBluetech::data()["REST_AUTH_PATH"];
        $this->REST_GSX_PATH = ConfigBluetech::data()["REST_GSX_PATH"];
        $this->REST_TABLE = ConfigBluetech::data()["REST_TABLE"];
        $this->consultToken();
    }


    public function consultToken(){
        $token = AuthModel::selectToken($this->REST_TABLE);
        if($token){
            $this->REST_AUTH_TOKEN  = $token['active_token_app'];
        }
    }

}
