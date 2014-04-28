<?php

//[i] Authorized Only
Route::group(array('before'=>'auth.sentry'), function(){
    Route::get('message/{conversation_id?}',array('as'=>'message.manage', 'uses'=>'Atlantis\Message\MessageController@getManage'));
    Route::get('message/show/{message_id?}',array('as'=>'message.show', 'uses'=>'Atlantis\Message\MessageController@getShow'));
    Route::controller('message','Atlantis\Message\MessageController');
});


//[i] API
Route::group(array('prefix'=>'api/v1'), function(){
    ## Messages API
    Route::post('messages/clear','Atlantis\Message\Api\V1\MessageController@clear');
    Route::resource('messages','Atlantis\Message\Api\V1\MessageController');
});