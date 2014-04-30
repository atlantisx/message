<?php namespace Atlantis\Message\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;


class Message extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'messages';

	/**
	 * The relationships that should be touched on save.
	 *
	 * @var array
	 */
	protected $touches = ['conversation'];

	/**
	 * The attributes that can be set with Mass Assignment.
	 *
	 * @var array
	 */
	protected $fillable = ['conversation_id', 'user_id', 'body'];

	/**
	 * Validation rules.
	 *
	 * @var array
	 */
	protected $rules = [
		'body' => 'required',
	];


	/**
	 * Conversation relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function conversation()
	{
		return $this->belongsTo('Atlantis\Message\Model\Conversation');
	}


	/**
	 * User relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function user()
	{
		return $this->belongsTo(Config::get('messenger::user_model'));
	}


	/**
	 * Participants relationship
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function participants()
	{
		return $this->hasMany('Atlantis\Message\Model\Participant', 'conversation_id', 'conversation_id');
	}


	/**
	 * Recipients of this message
	 *
	 * @var \Illuminate\Database\Eloquent\Relations\HasMany
     * @return Eloquent
	 */
	public function recipients()
	{
		return $this->participants()->where('user_id', '!=', $this->user_id)->get();
	}


    public function getCreatedWhenAttribute(){
        return \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
    }


    public function getUpdatedWhenAttribute(){
        return \Carbon\Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans();
    }


    public function getMetaAttribute($value){
        if( json_decode($value) ) {
            return json_decode($value);
        }else{
            return $value;
        }
    }

    public function setMetaAttribute($value){
        $this->attributes['meta'] = json_encode($value);
    }
}