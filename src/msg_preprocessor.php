<?php
/**
 * Message preprocessor php script.
 * @package oneEyedBot
 * @author threej [Jitendra Pal]
 * 
 * @version 0.1.0
 * 
*/

//telegram api communicator php script
require_once __DIR__."/communicator.php";

//Database manipulation php script
require_once __DIR__."/class/db_man_3j.php";

/* ###################### VALIDATE RECEIVED UPDATE ########################### */

/**
 * function to process received update from telegram webhook
 * @param json $update json expected
 * @return bool
*/
function update_processor($update_arr){
  
  //get the source/origin of the received update
  $update_src = check_source($update_arr);

  switch($update_src){
    case 'private':
      
      if(!file_exists('includes/processors/private_msg_processor.php')){
        exit;
      }      
      require_once 'includes/processors/private_msg_processor.php';

      new_private_message($update_arr);
    break;
    
    case 'group':
      return false;
    break;
    
    case 'supergroup':
      return false;
    break;
    
    case 'channel':
      return false;
    break;
    
    case 'inline_query':
      return false;
    break;

    case 'inline_query_result':
      return false;
    break;

    case 'callback_query':
      return false;
    break;

    case 'shipping_query':
      return false;
    break;

    case 'pre_checkout_query':
      return false;
    break;
    
    case 'poll':
      return false;
    break;

    case 'poll_answer':
      return false;
    break;

    default:
      parse_error($update_arr,1,0);
      die;
    break;
  }    
}

/* ###################### CHECK ORIGIN OF THE RECEIVED UPDATE ################ */

/**
 * @param array $update json decoded array of update received from telegram webhook
 * @return string returns the type of content received.
 */
function check_source($update){

  return $update['message']['chat']['type'] ??
  $update['edited_message']['chat']['type'] ??
  $update['channel_post']['chat']['type'] ??
  $update['edited_channel_post']['chat']['type'];

  if(isset($update['inline_query'])){return "inline_query";}
  if(isset($update['chosen_inline_result'])){return "chosen_inline_result";}
  if(isset($update['callback_query'])){return "callback_query";}
  if(isset($update['shipping_query'])){return "shipping_query";}
  if(isset($update['pre_checkout_query'])){return "pre_checkout_query";}
  if(isset($update['poll'])){return "poll";}
  if(isset($update['poll_answer'])){return "poll_answer";}
  COM::send_log($update);
  die;
}
