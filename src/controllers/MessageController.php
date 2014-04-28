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


    public function getShow($message_id=null){
        $message = \Message::find($message_id);

        #i: View data
        $data = array(
            'message' => $message
        );

        $this->layout->content = \View::make('message::show',$data);
    }
}