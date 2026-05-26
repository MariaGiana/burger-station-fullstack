<?php
class SuccessView{
    public $user = null;
    
    public function __construct($res){
    if($res != null)
            $this->user = $res->user_name;
    }
    public function seeSuccess(){
        require_once './templates/success.phtml';
    }
        
}