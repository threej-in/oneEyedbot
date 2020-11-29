<?php
/**
 * Functions related to the current package
 * @package one_eyed_bot
 * @author threej [Jitendra pal]
 * @version 0.1.0
*/

/**
 * Decide where to print the error.
 * 
 * @param string $error Error message which will be shown to end user
 * @param bool $error_log pass 1 if you want this error to log into error log file
 * @return bool
*/
function parse_error($error, $send_log = 1, $error_log = 1){
    if(DEBUG_MODE === true){
        print_r($error);
        echo"<br>";
    }else{

        if(!empty(ADMINID && $send_log === 1)){
            return COM::send_log($error);
        }
        if($error_log === 1){
            return error_log($error);
        }
    }
    return true;
}
