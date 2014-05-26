<?php namespace Atlantis\Message\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;
use Rhumsaa\Uuid\Uuid;


class Conversation extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'conversations';


	/**
	 * The attributes that can be set with Mass Assignment.
	 *
	 * @var array
	 */
	protected $fillable = ['subject'];


	/**
	 * Messages relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function messages()
	{
		return $this->hasMany('Atlantis\Message\Model\Message');
	}


	/**
	 * Participants relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function participants()
	{
		return $this->hasMany('Atlantis\Message\Model\Participant');
	}


	public function scopeForUser($query, $user = null)
	{
		$user = $user ?: \Auth::user()->id;

		return $query->join('participants', 'conversations.id', '=', 'participants.conversation_id')
		->where('participants.user_id', $user)
		->select('conversations.*');
	}


	public function scopeWithNewMessages($query, $user = null)
	{
		$user = $user ?: \Auth::user()->id;

		return $query->join('participants', 'conversations.id', '=', 'participants.conversation_id')
            ->where('participants.user_id', $user)
            ->where('conversations.updated_at', '>', \DB::raw('participants.last_read'))
            ->select('conversations.*');
	}


    public function scopeWithAllMessages($query, $user = null)
    {
        $user = $user ?: \Auth::user()->id;

        return $query->join('participants', 'conversations.id', '=', 'participants.conversation_id')
            ->where('participants.user_id', $user)
            ->select('conversations.*');
    }


    public function scopeWithMessageMeta($query,$meta){
        $query->whereHas('messages', function($q) use($meta){
            $q->where('meta',$meta);
        });
    }


    public function scopeWithMessage($query,$message_id){
        $query->whereHas('messages', function($q) use($message_id){
            $q->where('id',$message_id);
        });
    }


    public function scopeBroadcast($query){
        $query->whereHas('messages',function($query){
            $query->where('meta','{"type":"broadcast"}');
        })->orderBy('updated_at');
    }


    public function getSubjectAttribute($value){
        if( Uuid::isValid($value) ){
            $record = \App::make('Record');
            if( $record ){
                return $record::find($value)->detail->config->detail_title;
            }
        }

        return $value;
    }


    public function messageSend($sender,$message){
        try{
            $receivers = $this->participants()->get();

            #i: Check for participants
            if( empty($receivers) ) throw new \Exception('Receivers not found!');

            #i: Send all message to participants
            foreach($receivers as $receiver){
                #i: Create and attach new message
                $message_new = \Message::create([
                    'conversation_id' => $this->id,
                    'user_id' => $sender->id,
                    'body' => $message['body']
                ]);

                #i: Check if meta exist
                if( isset($message['meta']) ){
                    $message_new->meta = $message['meta'];
                    $message_new->save();
                }

                #i: User notification through email
                if( isset($message['notify']) ){
                    $notify = array(
                        'subject' => trans('message::message.text.notification_subject',$sender->toArray()),
                        'message' => $message_new,
                        'message_link' =>  \URL::to('message/show', $message_new->id)
                    );

                    $this->notifySend(
                        'message::emails.notification',
                        $sender,
                        $receiver->user,
                        $notify
                    );
                }
            }

        }catch(Exeption $e){
            return false;
        }

        return true;
    }


    public function notifySend($view,$sender,$receiver,$message){
        try{
            $data = array(
                'sender' => $sender->toArray(),
                'receiver' => $receiver->toArray()
            );

            #i: Data validations
            $validation = \Validator::make($data,array('receiver.email' => 'required|email'));
            if($validation->fails()) throw new \Exception($validation->messages()->first());

            #i: Check for view
            if( !\View::exists($view) ) throw new \Exception('View not exist!');

            #i: Merge data array
            $data = array_merge_recursive($data,$message);

            #i: Queue a notification email
            \Mail::queue($view,$data,function($message) use($data){
                $message
                    ->to($data['receiver']['email'])
                    ->subject($data['subject']);
            });

        }catch(Exception $e){
            return false;
        }

        return true;
    }


	public function getParticipantListAttribute($user = null)
	{
		$user = $user ?: \Sentry::getUser()->id;

		$participants = \DB::table('users')
            ->join('participants', 'users.id', '=', 'participants.user_id')
            ->where('users.id', '!=', $user)
            ->where('participants.conversation_id', $this->id)
            ->get();

		return $participants;
	}


    public function addParticipantById($receiver_id){
        $user_model_name = Config::get('message::user_model','\User');
        $user_model = new $user_model_name;

        try{
            #i: Find user
            $receiver = $user_model::find($receiver_id);

            #i: Create participant for current conversation
            Participant::create([
                'user_id' => $receiver->id,
                'conversation_id' => $this->id,
            ]);

        }catch(Exception $e){

        }
    }


	/**
	 * addParticipants : Add users to this conversation
	 *
	 * @param array $participants Emails list of all participants
	 * @return void
	 */
	public function addParticipants(array $participants)
	{
		$userModel = Config::get('messenger::user_model');
		$userModel = new $userModel;

		$participant_ids = [];

		if (is_array($participants))
		{
			if (is_numeric($participants[0]))
			{
				$participant_ids = $participants;
			}
			else
			{
				$participant_ids = $userModel->whereIn('email', $participants)->lists('id');
			}
		}
		else
		{
			if (is_numeric($participants))
			{
				$participant_ids = [$participants];
			}
			else
			{
				$participant_ids = $userModel->where('email', $participants)->lists('id');
			}
		}

		if(count($participant_ids))
		{
			foreach ($participant_ids as $user_id)
			{
				Participant::create([
					'user_id' => $user_id,
					'conversation_id' => $this->id,
				]);
			}
		}
	}

    public function getCreatedWhenAttribute(){
        return \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }


    public function getUpdatedWhenAttribute(){
        return \Carbon\Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans();
    }

}