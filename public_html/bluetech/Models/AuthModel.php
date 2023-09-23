<?php

namespace Models;

use PDO;

require_once __DIR__."/ConnectionModels.php";

class AuthModel
{

    static public function selectToken($table)
    {
        $stmt = Connection::connect()->prepare("SELECT * FROM $table LIMIT 1");

        if ($stmt->execute()) {
            return $stmt->fetch();
        } else {
            print_r(Connection::connect()->errorInfo());
        }
        $stmt->close();
        $stmt -= null;
    }

    static public function createToken($table, $data)
    {   $date = date('Y-m-d H:i:s');
        $stmt = Connection::connect()->prepare("INSERT INTO $table(active_token_app, created_at, updated_at) VALUES (:active_token_app, :created_at, :updated_at)");
        $stmt->bindParam(":active_token_app", $data,  PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $date);
        $stmt->bindParam(":updated_at", $date);
        if ($stmt->execute()) {
            return "ok";
        } else {
            print_r(Connection::connect()->errorInfo());
        }
        $stmt->close();
        $stmt -= null;
    }

    static public function updateToken($table, $id, $data)
    {
        $date_now = date("Y-m-d H:i:s");
        $stmt = Connection::connect()->prepare("UPDATE $table SET active_token_app=:active_token_app, updated_at=:updated_at WHERE id=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":active_token_app", $data->authToken, PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $date_now);
        if ($stmt->execute()) {
            return "ok";
        } else {
            print_r(Connection::connect()->errorInfo());
        }
        $stmt->close();
        $stmt -= null;

    }

}
?>