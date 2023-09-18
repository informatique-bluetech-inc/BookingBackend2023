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

    public function __construct()
    {
        $this->REST_CERT_PATH = Config::data()["REST_CERT_PATH"];
        $this->REST_CERT_PASS = Config::data()["REST_CERT_PASS"];
        $this->REST_ACCOUNT_ID = Config::data()["REST_ACCOUNT_ID"];
        $this->REST_SLL_KEY = Config::data()["REST_SSL_KEY"];
        $this->REST_AUTH_TOKEN_APPLE = Config::data()["REST_AUTH_TOKEN_APPLE"];
        $this->REST_AUTH_TOKEN = Config::data()["REST_AUTH_TOKEN"];
        $this->REST_BASE_URL = Config::data()["REST_BASE_URL"];
        $this->REST_SoldTo = Config::data()["REST_SoldTo"];
        $this->REST_ShipTo = Config::data()["REST_ShipTo"];
        $this->REST_AUTH_PATH = Config::data()["REST_AUTH_PATH"];
        $this->REST_GSX_PATH = Config::data()["REST_GSX_PATH"];
        $this->consultToken();
    }


    public function consultToken(){
        $token = AuthModel::selectToken("istore_three");
        if($token){
            $this->REST_AUTH_TOKEN  = $token['active_token_app'];
        }
    }

}
