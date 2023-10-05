<?php
class Logger
{

    /**
    * This methods write a log saving a message and the file where the message come from.
    */
    public function writeLog($logMessage, $currentFileName) {

        $logMessage = date("Y-m-d h:i:s") . " Logged by ". $currentFileName .". " . $logMessage;
        
        $folderStoreLogs = __DIR__ . "/logs_app"; //define the folder

        if (!file_exists($folderStoreLogs)){//if folder do not exist

            //create folder, give permissions and give it recursively
            $wasCreated = mkdir($folderStoreLogs, 0777, true);

            if($wasCreated == false){//if the folder was not created 
                echo "Error while locating logs folder, please resolve it before using the app";
                die;
            }
            
        }
        $logFileData = $folderStoreLogs."/log_" . date("Y_m_d") . ".log";
        $result = file_put_contents($logFileData, $logMessage . "\n", FILE_APPEND);

        if($result == false){
            echo "Error while writing in log file";
            die;
        }
    }
}
?>