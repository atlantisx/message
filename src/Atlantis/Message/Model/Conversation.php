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
		return $this->hasMany('Andheiberg\Messenger\Models\Message');
	}


	/**
	 * Participants relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function participants()
	{
		return $this->hasMany('Andheiberg\Messenger\Models\Participant');
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
            $detail = \App::make('Application\Detail');
            if( $detail ){
                return $detail::find($value)->application->config->detail_title;
            }
        }

        return $value;
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