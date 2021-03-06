<?php

/** Authorized Only */
Route::group(array('before'=>'auth.sentry'), function(){
    Route::get('message/{conversation_id?}', array('as'=>'message.manage.list', 'uses'=>'Atlantis\Message\MessageController@getManage'));
    Route::get('message/thread/{conversation_id?}', array('as'=>'message.thread', 'uses'=>'Atlantis\Message\MessageController@getThread'));
    Route::get('message/show/{message_id?}', array('as'=>'message.show', 'uses'=>'Atlantis\Message\MessageController@getShow'));
    Route::controller('message', 'Atlantis\Message\MessageController');
});


/** API */
Route::group(array('prefix'=>'api/v1'), function(){
    /** Messages API */
    Route::post('messages/clear','Atlantis\Message\Api\V1\MessageController@clear');
    Route::resource('messages','Atlantis\Message\Api\V1\MessageController');
});