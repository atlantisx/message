<?php namespace Atlantis\Message\Api\V1;

use Atlantis\Core\Controller\BaseController;
use Atlantis\Message\Model\Conversation;
use Atlantis\Message\Model\Participant;
use Atlantis\Message\Model\Message;


class MessageController extends BaseController{

    /*******************************************************************************************************************
     * Get All Conversation By User ID
     *
     * @params $user_id (GET) User ID
     ******************************************************************************************************************/
    public function index(){
        #i: Get input
        $get = \Input::all();

        #i: Retrieve all message for user conversations
        $conversation = \Conversation::with('messages')->forUser($get['user_id'])->get();

        return \Response::json($conversation);
    }


    /*******************************************************************************************************************
     * Create a Message
     *
     * @params $user_id (GET) User ID
     ******************************************************************************************************************/
    public function store($post=array()){
        $post = empty($post) ? \Input::all() : $post;

        try{
            #i: Message validation
            $validation = \Validator::make($post,array(
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'subject' => 'required',
                'body' => 'required'
            ));
            if( $validation->fails() ) throw new \Exception($validation->messages()->first());

            #i: Find conversation
            $conversation_id = (!empty($post['conversation_id']) ? $post['conversation_id'] : -1);
            $conversation = Conversation::find($conversation_id);

            #i: If conversation not exist create
            if(!$conversation){
                #i: Find receiver
                $receiver = \User::find($post['receiver_id']);

                #i: Create new conversation
                $conversation = new Conversation();
                $conversation->subject = $post['subject'];
                $conversation->save();

                #i: Add receiver as participant
                $conversation->addParticipantById($receiver->id);
            }

            #i: Find receiver user
            $sender = \User::find($post['sender_id']);

            #i: Send message to participants
            $conversation->messageSend($sender, $post);

            $post['_status'] = array(
                'type' => 'success',
                'message' => 'Successfully sending a message!'
            );

        } catch(Exception $e){
            $post['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        #i: Server response
        return \Response::json($post);
    }


    public function show($id){
        $get = \Input::all();

        try{
            $message = \Message::find($id);
            if($message) \Response::json($get = $message);

        } catch(Exception $e){
            $get['_status'] = array(
                'type' => 'error',
                'message' => $e->getMessage()
            );
        }

        return \Response::json($get);
    }


    public function destroy($id){
        $delete = \Input::all();

        try{

        } catch(Exception $e){

        }

        return \Response::json($delete);
    }
}