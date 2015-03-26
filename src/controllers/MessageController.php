<?php namespace Atlantis\Message;

use Atlantis\Core\Controller\BaseController;


class MessageController extends BaseController {

    public function getIndex(){
        $this->getManage();
    }


    public function getManage(){
        #i: Get conversations for current user
        $conversations = \Conversation::forUser($this->user->id)->withMessageMeta('{"permission":{"reply":"staff"}}')->get();
        $broadcasts = \Conversation::forUser($this->superuser->id)->broadcast()->get();

        #i: View data
        $data = array(
            'conversations' => $conversations,
            'broadcasts' => $broadcasts
        );

        $this->layout->content = \View::make('message::manage',$data);
    }


    public function getThread($conversation_id=null){
        #i: Get conversation
        $conversation = \Conversation::find($conversation_id);

        if( $this->user_realm == 'student' ){
            $messages = $conversation->messages()->where('meta','{"permission":{"reply":"staff"}}')->get();
        }else{
            $messages = $conversation->messages;
        }

        #i: View data
        $data = array(
            'conversation' => $conversation,
            'messages' => $messages,
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