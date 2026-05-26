<?php
require_once './app/model/auth.model.php';
require_once './app/view/view.php';
require_once './app/model/abstract.model.php';
require_once './libs/jwt.php';
class AuthController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new UserModel();
        $this->view = new view();
    }

    public function getToken() {
        // obtengo el email y la contraseña desde el header
        $auth_header = $_SERVER['HTTP_AUTHORIZATION']; // "Basic dXN1YXJpbw=="
        $auth_header = explode(' ', $auth_header); // ["Basic", "dXN1YXJpbw=="]
        if(count($auth_header) != 2) {
            return $this->view->showResult("Error en los datos ingresados", 400);
        }
        if($auth_header[0] != 'Basic') {
            return $this->view->showResult("Error en los datos ingresados", 400);
        }
        $user_pass = base64_decode($auth_header[1]); // "webadmin:admin"
        $user_pass = explode(':', $user_pass); // ["webadmin", "admin"]
        // Buscamos El usuario en la base
        $user = $this->model->getUserByUserName($user_pass[0]);
        // Chequeamos la contraseña
        if($user == null || !password_verify($user_pass[1], $user->password)) {
            return $this->view->showResult("Error en los datos ingresados", 400);
        }
        // Generamos el token
        $token = createJWT(array(
            'sub' => $user->id,
            'user_name' => $user->user_name,
            'exp'=> time() + 3600
        ));
        return $this->view->showResult($token);
    }
}