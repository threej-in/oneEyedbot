<?php
/**
 * 
 * @package one_eyed_bot [group moderator bot]
 * @author threej [ Jitendra Pal ]
 * @link http://threej.in/docs/telegram/bots/one_eyed_bot/index.php
 * 
 * licence under GNU [General Public License] v3.0
 * 
 * @version 0.1.0
*/

/*######################## set webhook using query parameter ######################### */
if(isset($_GET)){

  if(isset($_GET['AAE8QCd9apdfwedwd3FPEmBxBEhlfPv-o_swbhkurl'])){
    require_once __DIR__."/class/communicator.php";

    $parameter = ['method'=>'setwebhook','url' => $_GET['AAE8QCd9apdfwedwd3FPEmBxBEhlfPv-o_swbhkurl']];
    $result = COM::curl_handler($parameter, 1);
    print_r($result);
    return true;

  }else{
    echo "<h2 style=\"background-color:black;width:fit-content;color:white;box-shadow: -135px 0px red inset; padding:4px;text-align:right;\">404 World is missing</h3><br>"; //show error to unknown visitors 
  }
}else{
  echo "<h2 style=\"background-color:black;width:fit-content;color:white; padding:4px;\">404 World is missing</h3><br>"; //show error to unknown visitors 
}

/** ####################### Check if update is coming from telegram api ############### */

//general functions php script
require_once __DIR__."/includes/gen_fun_3j.php";

//get update and send for processing
$update = file_get_contents("php://input");

if(empty($update) || !is_string($update)){
  error_log("wrong update received".to_string($update));
  return false;
}

$update_arr = json__decode($update, 1);
if(!$update_arr){return false;}

//include msg_preprocessor
require_once __DIR__.'/msg_preprocessor.php';

update_processor($update_arr);

return true;
?>
