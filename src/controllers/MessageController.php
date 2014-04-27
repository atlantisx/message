<?php namespace Atlantis\Message;


class MessageController extends \Atlantis\Admin\BaseController {
    protected $layout = 'admin::layouts.common';

    public function getIndex(){
        $this->layout->content = View::make('message.inbox');
    }
}