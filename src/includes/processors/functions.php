<?php
/**
 * TGBOTMODULE - Easy to use module for interacting with available methods in telegram bot api
 * 
 * @package tgbotmodule
 * @author threej [Jitendra Pal]
 * @link http://threej.in/docs/telegram/bots/php/files/tgbotmodule
 * @version 0.1.0
*/

/**
 * Class contains easy to use function for accessing the available methods in Telegram bot api
 * 
 * @param int|double $chat_id
 * @param int $msg_id
 * 
 */
class jarvis_functions{

    /**
     * Initializes chat_array and msg_array
     * @param int $chat_id chat_id received in the update
     * @param int $msg_id msg_id received in the update
     */
    function __construct($chat_id, $msg_id)
    {
        define('MSGARR', ['message_id'=>$msg_id]);
        define('CHATARR', ['chat_id'=>$chat_id]);
        define('PMHTML',['parsing-mode'=>'HTML']);
    }
    
    /**
     * Executes curl_handler function from COM class in communicator.php
     */
    private function execute($parameter, $r = 0, $is_req_chat_id = true)
    {
        $is_req_chat_id == true && $parameter += CHATARR;
        return COM::curl_handler($parameter, $r);
    }

    /**
     * Deletes existing message sent by bot in private chat and groups if have proper permission.
     * 
     * @param bool $r -send 1 if response needed
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function delete_msg($r = 0){
        
        return $this->execute([
            'method'=>'deletemessage',
        ]+ MSGARR, $r);
    }
    
    /**
     * Edit messages sent by the bot.
     * 
     * @param string $text -replacing text
     * @param array $reply_markup -array of inline keyboard as 
     * ['inline_keyboard'=>[[[text & (url | login_url | callback_data |
     *  switch_inline_query | switch_inline_query_current_chat | callback_game | pay)]]]
     * ]
     * @param bool $r -send 1 if response needed
     * @param string $inline_msg_id
     * @param bool $disable_web_preview
     * 
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function edit_msg(
        $text,
        $r=0,
        $reply_markup=null,
        $inline_msg_id=null,
        $disable_web_preview=true)
    {

        return $this->execute([
            'method'=>'editmessagetext',
            'text'=>$text,
            'reply_markup'=> $reply_markup,
            'inline_message_id'=>$inline_msg_id,
            'disable_webpage_preview'=> $disable_web_preview
        ]+ MSGARR + PMHTML, $r);
    }

    /**
     * Edit message caption of message sent by bot.
     * 
     * @param string $caption -replacing text
     * @param array $reply_markup -array of inline keyboard as 
     * ['inline_keyboard'=>[[[text & (url | login_url | callback_data |
     *  switch_inline_query | switch_inline_query_current_chat | callback_game | pay)]]]
     * ]
     * @param bool $r -send 1 if response needed
     * @param string $inline_msg_id
     * 
     * @return array|bool - Curl response as array if $r set 1, else boolean
     * 
     */
    function edit_msg_caption(
        $caption,
        $r=0,
        $reply_markup=null,
        $inline_msg_id=null)
    {
        return $this->execute([
            'method'=>'editmessagecaption',
            'caption'=>substr($caption, 0,1022),
            'reply_markup'=> $reply_markup,
            'inline_message_id'=>$inline_msg_id,
        ]+ MSGARR + PMHTML, $r);
    }

    /**
     * forward message
     * @param int|string $from_chat
     * @param bool $send_notification
     * @param bool $r -send 0 or 1 if response needed from curl_handler
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function forward_msg($from_chat, $r = 0, $send_notification = true){
        
        return $this->execute([
            'method'=>'forwardmessage',
            'from_chat_id'=>$from_chat,
            'disable_notification'=>!($send_notification)
        ]+ MSGARR, $r);
    }

    /**
     * forward copied message
     * @param int|string $from_chat
     * @param bool $send_notification
     * @param bool $r -send 0 or 1 if response needed from curl_handler
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function forward_copied_msg(
        $from_chat,
        $src_msg_id,
        $r = 0,
        $caption = null,
        $reply_markup = "",
        $reply_to_original_msg = false,
        $send_notification = true
        ){
        return $this->execute([
            'method'=>'copymessage',
            'from_chat_id'=>$from_chat,
            'message_id'=>$src_msg_id,
            'capiton'=>$caption,
            'reply_to_message_id'=>$reply_to_original_msg == true && MSGARR['message_id'],
            'allow_sending_without_reply'=>true,
            'disable_notification'=>!($send_notification),
            'reply_markup'=>$reply_markup
        ] + PMHTML, $r);
    }

    /**
     * bot will leave the chat
     * @param bool $r -send 0 or 1 if response needed from curl_handler
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function leave_chat($r = 0){
        return $this->execute([
            'method'=> 'leavechat',
        ], $r);
    }

    /**
     * Pin message
     * @param bool $send_notification
     * @param bool $r -send 0 or 1 if response needed from curl_handler
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function pin_msg($send_notification = true, $r = 0){

        return $this->execute([
            'method'=>'pinchatmessage',
            'disable_notification'=>!($send_notification)
        ]+ MSGARR, $r);
    }

    /**
     * Removes the menu keyboard/button
     * @param string $msgtouser - Indicate user that you have removed the menu buttons.
     * @param bool $r - pass 1 to get response
     * @param bool $selective - Buttons will be removed for selective user, useful in the group
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function remove_keyboard(
        $msgtouser,
        $r = 0,
        $selective = true
        ){
        return $this->send_message($msgtouser,1,[
            'remove_keyboard'=>true,
            'selective'=> $selective
        ], $r);
    }

    /**
     * Report error to user and admin
     * @param mixed $msg_for_admin
     * @param int|double $chat_id
     * @param string $msg_for_user
     * @param bool $r -send 0 or 1 if response needed from curl_handler
     * 
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function report_error(
        $msg_for_admin,
        $msg_for_user = "Unknown error occured",
        $r = 0
        ){
        $this->send_message($msg_for_user,$r);
        COM::send_log($msg_for_admin);        
    }

    /**
     * Actions notify user about bot's current status
     *
     * @param string $action - Supported actions (typing | upload_photo | record_video | upload_video
     * | record_audio | upload_audio | upload_document | find_location | record_video_note | record_audio_note)
     * @return NULL no return value
     */
    function send_action($action = "typing", $r = 0){
        
        return $this->execute([
            'method'=>'sendchataction',
            'action'=> $action
        ],$r);
    }
    
    /**
     * sends text message with reply_markup
     * @param string $text -text message to send
     * @param bool $r send 1 if response needed from telegram
     * @param array $markup_parameter
     * Array of
     * *
     * * ReplyKeyboardMarkup as 
     * * * ['keyboard'=>[
     * * * * [['text'=>'string'],['text'=>'','request_contact'=>bool]],
     * * * * [['text'=>'','request_location'=>bool]],
     * * * * [['text'=>'','request_poll'=>['type'=>'quiz']]]],
     * * * 'resize_keyboard'=>bool,
     * * * 'selective'=>bool
     * * * ]
     * *
     * * InlineKeyboardMarkup as 
     * * * ['inline_keyboard'=>
     * * * * [[[ 'text'=>'string' & (url | login_url | callback_data |
     * switch_inline_query | switch_inline_query_current_chat | callback_game | pay)]]
     * * * * ]
     * * * ]
     * *
     * * ReplyKeyboardRemove as ['remove_keyboard'=>'true','selective'=>bool]
     * *
     * * ForceReply as ['force_reply'=>bool, 'selective'=>bool]
     * *
     * @param int $reply_msgid - Reply to message ID
     * @param bool $web_preview - Disable web page preview
     * @param bool $send_notification - send notification
     * @param bool $allow_wo_replymsg - Pass True, if the message should be sent even if the specified replied-to message is not found
     *
     * @return array|bool - Curl response as array if $r set 1, else boolean
     */
    function send_message(
        $text,
        $r = 0,
        $markup_parameter = "",
        $reply_msgid = true,
        $noweb_preview = true,
        $send_notification = true,
        $allow_wo_replymsg = true
        ){
            
        return $this->execute([
            'method'=>'sendmessage',
            'text'=> $text,
            'reply_markup'=> $markup_parameter,
            'reply_to_message_id'=> $reply_msgid ? MSGARR['message_id']: null,
            'disable_web_page_preview'=>$noweb_preview,
            'disable_notification'=>!$send_notification,
            'allow_sending_without_reply'=> $allow_wo_replymsg
        ] + PMHTML, $r);
    }
    
    /**
     * Send sticker if allowed
     * @param string $file_id
     * @param bool $r send 1 if response needed from telegram api
     * @return bool|array 
     */
    function send_sticker($file_id, $r = 0){

        return $this->execute([
            'method' => 'sendsticker',
            'sticker' => $file_id
        ],$r);
    }

    /**
     * Set bot commands
     * @param array $param Array consisting list of bot command as key and its description as value
     * @return array
     */
    function set_commands($parameter, $r){
        $botcommand = [];
        $i = 0;
        foreach($parameter as $k => $v){
            $botcommand[$i++] = [
                'command'=>$k,
                'description'=>$v
            ];
        }

        return $this->execute([
            'method'=>'setmycommands',
            'commands'=>$botcommand
        ], $r, false);
    }

    function unpin_msg($r = 0){

        return $this->execute([
            'method'=>'unpinchatmessage',
        ], $r);
    }

    
    function unpin_all_msg($r = 0){

        return $this->execute([
            'method'=>'unpinallchatmessages',
        ],$r);
    }
}
