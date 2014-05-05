<?php namespace Atlantis\Message\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

class Participant extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'participants';

	/**
	 * The attributes that can be set with Mass Assignment.
	 *
	 * @var array
	 */
	protected $fillable = ['conversation_id', 'user_id', 'last_read'];

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
		return $this->belongsTo('User');
	}


	public function scopeMe($query, $user = null)
	{
		$user = $user ?: \Auth::user()->id;

		$query->where('user_id', '=', $user);
	}


	public function scopeNotMe($query, $user = null)
	{
		$user = $user ?: \Auth::user()->id;

		$query->where('user_id', '!=', $user);
	}

}