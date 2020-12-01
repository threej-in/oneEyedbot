<?php
/**
 * Private message processor
 * @package oneEyedBot
 * @author threej[Jitendra Pal]
 * @version 0.1.0
 * 
*/

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
          $text = null,
          $reply_msgid = null,
          $command = null;

  function __construct($update_arr){
    isset($update_arr['message']) ?
      $this->extract_message($update_arr['message']) : 0
    ;
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

    //reply message extract
    if(isset($msg['reply_to_message'])){
      $reply = $msg['reply_to_message'];
      $this->reply_msgid = $reply['message_id'] ?? null;
    }

    //service message extract
    isset($msg['pinned_message']) ? $this->text = 'service_message' :  0 ;

  }
}

/**
 * process private messages
 * 
 * @param array $update_arr - decoded update array
 * @return bool
 */
function new_private_message($update_arr){

  $msg = new private_message($update_arr);
  
  $text = $msg->text;

  //process entities with multiple commands/hashtags
  if(isset($update_arr['message']['entities'])){
    foreach($update_arr['message']['entities'] as $k => $v){
      if($v['type'] === 'bot_command' || $v['type'] === 'hashtag'){
        $result = execute_command(substr($text,$v['offset'],$v['length']), $msg);
        $result === false && COM::send_log($update_arr);
      }
    }
  }elseif($text === 'service_message'){
    //process service messages
    $result = execute_command($text, $msg);
    $result === false && COM::send_log($update_arr);    
  }else{
    //unknow message
    COM::send_log($update_arr);
  }
  return true;
}

function execute_command($command, $msg){

  $jarvis = new jarvis_functions($msg->chat_id, $msg->msg_id);

  switch($command){

    case 'hello':
      $jarvis->send_message('Hey! how are you');
    break;

    //delete replied message
    case '/delete':
    case '#delete':
      if($msg->reply_msgid === null){
        $jarvis->send_message('Message to delete is not found, please reply to the message you want to delete');
      }else{
        $jarvis->delete_msg($msg->reply_msgid);
        //delete the command also
        $command === '#delete' && $jarvis->delete_msg($msg->msg_id);
      }    
    break;

    //pin replied msg
    case '/pin':
      if($msg->reply_msgid === null){  
        $jarvis->send_message('Message to pin is not found, please reply to the message you want to pin');
      }else{
        $jarvis->pin_msg($msg->reply_msgid);
      }
    break;

    case '#pin':
      if($msg->reply_msgid === null){  
        $jarvis->pin_msg($msg->msg_id);
      }else{
        $jarvis->send_message('To pin replied message use /pin command');
      }
    break;
    
    //delete service messages
    case 'service_message':
      $jarvis->delete_msg($msg->msg_id);
    break;

    //start command
    case '/start':
      $jarvis->send_sticker(BLINKINGCHIBI);
      $jarvis->send_action('typing');
      $jarvis->send_message(WAVINGHAND." Hello $msg->user_fname,\n<b>I am a group moderator bot and I can help you manage your groups and channels.</b>",
        ['inline_keyboard'=>[[['text'=>POINTINGHAND.' CLICK TO GET STARTED '.ROCKET,'callback_data'=>'getstarted']]]]);
    break;

    //unpin replied message
    case '/unpin':
      if($msg->reply_msgid === null){  
        $jarvis->send_message('Message to unpin is not found, please reply to message you want to unpin.');
      }else{
        $jarvis->unpin_msg($msg->msg_id);
      }
    break;

    //unpin all message
    case '/unpin_all':
      
        $jarvis->unpin_all_msg();
      
    break;

    default:
      $jarvis->send_message('Unknown command, type /help for more information...');
      return false;
    break;

    return true;
  }
}
