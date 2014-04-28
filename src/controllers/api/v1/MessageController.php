<?php namespace Atlantis\Message\Api\V1;

use Atlantis\Admin;

class MessageController extends \BaseController{

    /*******************************************************************************************************************
     * Get All Conversation By User ID
     *
     * @params $user_id (GET) User ID
     ******************************************************************************************************************/
    public function index(){
        $get = \Input::all();

        $conversation = \Conversation::forUser($get['user_id'])->get();
        $conversation->load('messages');

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
            $conversation_id = (!empty($post['conversation_id']) ? $post['conversation_id'] : 0);

            #i: Message validation
            $validation = \Validator::make($post,array(
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'subject' => 'required',
                'body' => 'required'
            ));
            if( $validation->fails() ) throw new \Exception($validation->messages()->first());

            #i: Find conversation
            $conversation = \Conversation::find($conversation_id);

            if(!$conversation){
                #i: Create new conversation
                $conversation = new \Conversation();
                $conversation->subject = $post['subject'];
                $conversation->save();

                #i: Add participants/receivers
                \Participant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $post['receiver_id']
                ]);
            }

            #i: Create and attach new message
            $message_new = \Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $post['sender_id'],
                'body' => $post['body']
            ]);

            #i: Check if status exist
            if( isset($post['meta']) ){
                $message_new->meta = $post['meta'];
                $message_new->save();
            }

            #i: User notification through email
            if( isset($post['notify']) ){
                $receivers = $conversation->participants;
                if( !$receivers ) throw new \Exception('Receivers not found!');

                #i: Send notification to all receivers
                foreach($receivers as $receiver){
                    $data = array(
                        'sender' => \User::find($message_new->user_id)->toArray(),
                        'receiver' => \User::find($receiver->user_id)->toArray(),
                        'message' => $message_new,
                        'message_link' =>  \URL::to('message/show', $message_new->id)
                    );

                    #i: Data validations
                    $validation = \Validator::make($data,array('receiver.email' => 'required|email'));
                    if($validation->fails()) throw new \Exception($validation->messages()->first());

                    #i: Check for view
                    if( !\View::exists('message::emails.notification') ) throw new \Exception('View not exist!');

                    #i: Queue a notification email
                    \Mail::queue('message::emails.notification',$data,function($message) use($data){
                        $message
                            ->to($data['receiver']['email'])
                            ->subject(trans('message::message.text.notification_subject',$data['sender']));
                    });
                }
            }

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