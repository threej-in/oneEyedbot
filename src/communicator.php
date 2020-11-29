<?php
/**
 * PHP script for establishing communication with Telegram bot API
 * @package oneEyedBot
 * @author threej[Jitendra Pal]
 * @version 0.1.0
*/

/*####################### INCLUDE CONFIGURATION FILE ##################################*/
require_once __DIR__.'/config_3j.php';

/*######################## INCLUDE NATIVE FUNCTION PHP SCRIPT ##########################*/
require_once __DIR__."/includes/native_fun_3j.php";


if(empty(BOT_TOKEN)){
  parse_error("Bot_token must not be empty.<br>You can get one from http://t.me/botfather");
  exit;
}
define('API_URL', "https://api.telegram.org/bot".BOT_TOKEN."/");

/**
 * Class for handling communications with telegram api
 * @since 0.1.0
 * @author threej [Jitendra Pal]
 * @link http://threej.in/docs/telegram/bots/php/general_files/communicator.php
 */
abstract class COM{

  /* ########################### UPDATES RETRIEVAL METHOD ############################# */
  /**
   * Fetches update from telegram api. Only for development purpose
   */
  public function get_updates($limit = 2, $timeout = 3600, $offset = -1){
    $param = [
      'limit'=>$limit,
      'timeout'=>$timeout,
      'offset'=>$offset
    ];

    $link = "https://api.telegram.org/bot1404988951:AAF5tWawu4NpsbOJpC-dv-SXXORI1J5qItM/getupdates";
    $h = curl_init($link);
    curl_setopt_array($h,[
       CURLOPT_RETURNTRANSFER=>true,
       CURLOPT_POST => true,
       CURLOPT_POSTFIELDS => json_encode($param),
       CURLOPT_HTTPHEADER => array('content-Type : application/json')
      ]
    );
    return json_decode(curl_exec($h),true);
  }

  /* ########################### CURL HANDLER METHOD ############################# */
  /**
   * curl_handler function handles request and response from and to the telegram api
   * @return mixed TRUE on success and response array if needed and string on failure
   * @param array $parameter parameters array as specified in telegram bots api documents
   * @param bool $response_needed If you need the response from curl then send 1
   */
  public static function curl_handler($parameter, $response_needed = 0){

    (empty($parameter)) && $parameter = array(); //declare an empty array if $parameter is empty
    
    if(!is_array($parameter)){
      parse_error("Parameters must be an array\n");
      return false;
    }
    if(!isset($parameter['method'])){
      parse_error("Method required to send the data\n");
      return false;
    }

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL=>API_URL,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_POST=>TRUE,
        CURLOPT_POSTFIELDS=>json_encode($parameter),
        CURLOPT_HTTPHEADER=>array('content-Type: application/json'),
        CURLOPT_CONNECTTIMEOUT=>10,
        CURLOPT_TIMEOUT=>30
      ]
    );

    $response = json_decode(curl_exec($ch),true);
    
    if(isset($response['description']) && $response['description'] == 'chat not found'){
      parse_error("ADMINID or chat_id is incorrect");
      return false;
    }
  
    $server_r_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err_no = intval(curl_errno($ch));

    parse_error("Server response code: $server_r_code", 0,0);
    parse_error("Curl error:".curl_error($ch), 0,0);
    
    
    if($err_no !== 0 || $server_r_code !== 200) {
      //log and return the error response received from telegram api
      parse_error(curl_error($ch)."\n\n".$response['description']);
      return ($response_needed === 1) ? $response['description'] : true ;

    }
    curl_close($ch);
    
    return ($response_needed === 1) ? $response : true ;

  }
  //End of curl_handler method

  /* ########################### SEND LOG METHOD ############################# */
  /**
   * function to report errors to admin directly by sending a private chat on telegram
   * @param mixed $msg log message which is sent to admin.
   * @param bool $r send 1 if response needed.
   * @return bool/array arrar if $r set 1 else boolean.
   */
  public static function send_log($msg, $r = 0){
    
    if(!empty(ADMINID)){

      $msg = to_string($msg);

      if(empty($msg)){
        return COM::send_log(null);
      }

      $parameter = [
        'method'=>'sendmessage',
        'chat_id' => ADMINID,
        'text' => $msg,
        'parse_mode' => 'HTML'
      ];
      return COM::curl_handler($parameter, $r);
    }
  }
  //end of send log method

}
