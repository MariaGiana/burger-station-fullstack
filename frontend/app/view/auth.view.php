<?php

class AuthView {
    private $user = null;

    public function showLogin($error = '') {
        require './templates/forms/form_login.phtml';
    }
}