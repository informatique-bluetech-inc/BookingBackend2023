<?php

namespace Helpers;

class LogMsg
{
    static function message($msg, $level = 'info', $file = 'main.log'): void{
        $levelStr = '';

        switch ( $level )
        {
            case 'info':
                $levelStr = 'INFO';
                break;

            case 'warning':
                $levelStr = 'WARNING';
                break;

            case 'error':
                $levelStr = 'ERROR';
                break;
        }

        $date = date( 'Y-m-d H:i:s' );

        $msg = (string)  json_encode($msg, true);

        $msg = sprintf( "[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL );
        file_put_contents( $file, $msg, FILE_APPEND );

    }

}
