<?php
require_once './app/view/success.view.php';
class SuccessControler{
    private $view;
    public function __construct($res = null) 
{
    $user = ($res !== null && isset($res->user)) ? $res->user : null;

    $this->view = new SuccessView($user);
    }
    public function showSuccess(){
        $this->view->seeSuccess();
    }
}