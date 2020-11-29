<?php
/**
 * This file contains general functions which can be used in any php script/program
 * @author threej [Jitendra Pal]
*/

/**
 * converts json string to associative array only if valid json string is passed 
 * else false is returned.
 * 
 * @param json $data json string expected
 * @param int $r send 1 if decoded array needed as response
 * @return array|false json decoded array or boolean value
 */
function json__decode($data, $r = 0){
    if(!is_string($data)){return false;}
    $decoded = json_decode($data, true);
    if(json_last_error() !== 0){return false;}
    return $r == 1 ? $decoded : true;
}

/**
 * Converts any data type to string value
 * @param mixed $data
 * @return string
 */
function to_string($data){
    if(is_string($data)){

        $decoded = json_decode($data, true);
        $err = json_last_error();
        //if json_decode function decodes the string successfully then build the http query.
        ($err > 0 && $err < 5) || $data = http_build_query($decoded, ' ', ' ');
        
    }elseif(is_array($data) || is_object($data)){
    
        $data = http_build_query($data, '', ' ');
    }else{
        if($data == null){
            $data = json_encode(debug_backtrace());
        }else{
            $data = strval($data);
        }
    }

    //string cleaning for more readability
    $find = ['/%2f/i','/%5D/i', '/%5B/i', '/\s+/'];
    $replace = ['/', ']', '[', ' '];
    $result = preg_replace($find,$replace, $data);
    return $result;
}
