<?php
/**
 * Private message processor
 * @package oneEyedBot
 * @author threej[Jitendra Pal]
 * @version 0.1.0
 * 
*/

define('BLINKINGCHIBI', 'CAACAgIAAxkBAAIDd1-EXuK2saBbv_6S6RTqjF11KV-zAALIAAMKu78k69LmAvFIA4gbBA');

//telegram functions php script
file_exists(__DIR__.'/functions.php') ? require_once __DIR__."/functions.php" : exit ;


class private_message{
  public  $from_bot = false,
          $msg_id = NULL,
          $chat_id = NULL,
          $chat_fname,
          $chat_lname,
          $chat_usrname,
          $chat_type,
          $chat_title,
          $user_id,
          $user_fname,
          $user_lname,
          $user_usrname,
          $user_lang_code,
          $text = '';

  function __construct($update_arr){
    $this->extract_message($update_arr['message']);
  }
  function getresrc(){
    return $this;
  }

  //extract relevant data from received update.
  private function extract_message($msg){
    
    $this->msg_id = $msg['message_id'];
    $chat = $msg['chat'];
    $user = $msg['from'];

    /* Note here '??' is equivalent to isset function. If specified array key is not found 
       then null will be assigned to the variable*/
    $this->chat_id = $chat['id'] ?? null;
    $this->chat_type = $chat['type'] ?? null;
    $this->chat_title = $chat['title'] ?? null;
    $this->chat_fname = $chat['first_name'] ?? null;
    $this->chat_lname = $chat['last_name'] ?? null;
    $this->chat_usrname = $chat['username'] ?? null;
    
    $this->user_id = $user['id'] ?? null;
    $this->from_bot = $user['is_bot'] ?? null;
    $this->user_fname = $user['first_name'] ?? null;
    $this->user_lname = $user['last_name'] ?? null;
    $this->user_usrname = $user['username'] ?? null;
    $this->user_lang_code = $user['language_code'] ?? null;

    $this->text = $msg['text'] ?? null;
  }
}

function new_private_message($update_arr){

  $msg = new private_message($update_arr);
  
  $jarvis = new jarvis_functions($msg->chat_id, $msg->msg_id);
  $jarvis->send_message('Thanks for starting me!');
  
}
