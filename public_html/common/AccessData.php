<?php
class AccessData
{

    // ----- CONNECTION VARIABLES -------------//
    /** Connection link to database */
    var $connection;
    
    /** Data result from database after execute a query */
    var array $retrievedRecords;

    /** Quantity of records that were affected after an insert, update or delete */
    var int $quantityRowsAffected;

    // ----- OTHER VARIABLES -------------//
    var $errorMessage;//in case of an error this contains the error message



    /**
     * Just the constructor
     */
    function __construct(){

    }



    /** 
     * This method creates a connection to a database 
    */
    private function connectToDatabase(){

        $databaseUser = "root";
        $databasePassword = "root";
        $databaseName = "booking_bluetech";
        $databaseHost = "mysql";
        $databasePort = "3306";
        
        try {
            
            $stringConnection = "mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName";
            $this->connection = new PDO($stringConnection, $databaseUser, $databasePassword);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;//true because we made a connection

        }catch(\Exception $e){
            $this->errorMessage = $e->getMessage();
            return false;//false because we could not make a connection
        }
    }



    /**
     * This method execute a query (in a database) and get the results
     */
    function retrieveData($sqlQuery){
            
        if($this->connectToDatabase() == false) return false;
        
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $result = $this->connection->prepare($sqlQuery);
            $result->execute();

            $i = 0;
            $temporalArray = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $temporalArray[$i] = $row;//array_map('utf8_encode', $row); deprecated
                $i++;
            }
            $this->retrievedRecords = $temporalArray;
            return true;//true because we executed a query to get data

        }catch(\Exception $e){
            $this->errorMessage = $e->getMessage();
            return false;//false because we did not execute a query and did not get data
        }
    }

    /**
     * This method execute a query (in a database) to insert, update or delete data.
     */
    function executeQueryOperation($sqlQuery){
            
        if($this->connectToDatabase() == false) return false;
        
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $result= $this->connection->prepare($sqlQuery);
            $result->execute();

            $this->quantityRowsAffected = $result->rowCount();
            
            return true;//true because we executed a query

        }catch(\Exception $e){
            $this->quantityRowsAffected = 0;
            $this->errorMessage = $e->getMessage();
            return false;//false because we did not execute a query
        }
    }

}
?>