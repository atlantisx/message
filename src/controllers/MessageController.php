<?php namespace Atlantis\Message;


class MessageController extends \Atlantis\Admin\BaseController {
    protected $layout = 'admin::layouts.common';


    public function getIndex(){
        $this->getManage();
    }


    public function getManage($conversation_id=null){
        #i: Get conversations for current user
        $conversations = \Conversation::forUser($this->user->id)->get();

        #i: Get current conversation id
        if(empty($conversation_id)) $conversation_id = $conversations->first()->id;

        #i: Get current messages
        $messages = $conversations->find($conversation_id) ? $conversations->find($conversation_id)->messages : null;

        #i: View data
        $data = array(
            'conversations' => $conversations,
            'messages' => $messages,
            'conversation_id' => $conversation_id
        );

        $this->layout->content = \View::make('message::manage',$data);
    }


    public function getThread($conversation_id=null){
        #i: Get conversations for current user
        $conversations = \Conversation::forUser($this->user->id)->get();

        #i: Get current messages
        $conversation = $conversations->find($conversation_id);

        #i: View data
        $data = array(
            'conversation' => $conversation,
            'sender' => \User::find($this->user->id),
            'receiver' => \User::find($conversation->messages[0]->user_id)
        );

        $this->layout->content = \View::make('message::thread',$data);
    }


    public function getShow($message_id=null){
        $message = \Message::find($message_id);

        #i: View data
        $data = array(
            'message' => $message,
            'sender' => \User::find($this->user->id),
            'receiver' => \User::find($message->user_id)
        );

        $this->layout->content = \View::make('message::show',$data);
    }
}