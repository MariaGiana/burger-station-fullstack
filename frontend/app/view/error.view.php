<?php
class ErrorView{
    public $user = null;
    
    public function __construct($res){
    if($res != null)
            $this->user = $res->user_name;
    }
    public function seeError($error, $redir){
        require_once './templates/error.phtml';
    }
        
}