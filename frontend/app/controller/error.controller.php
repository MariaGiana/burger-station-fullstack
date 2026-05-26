<?php
require_once './app/view/error.view.php';
class ErrorControler{
    private $view;
    public function __construct($res = null) 
{
    $user = ($res !== null && isset($res->user)) ? $res->user : null;

    $this->view = new ErrorView($user);
    }
    public function showError($error,$redir){
        $this->view->seeError($error,$redir);
    }
}