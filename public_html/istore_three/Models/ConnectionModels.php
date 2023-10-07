<?php

namespace Models;


use PDO;

class Connection
{
    static  public function  connect(){
        $link = new PDO('mysql:host=mysql;port=3306;dbname=booking_bluetech', "root", "root");
        $link->exec("set names utf8");
        return $link;
    }
}
