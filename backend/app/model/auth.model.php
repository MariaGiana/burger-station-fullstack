<?php
require_once './app/model/abstract.model.php';
class UserModel extends modelAbstract {
    public function __construct() {
       parent::__construct();
    }
 
    public function getUserByUserName($user_name) {    
        $query = $this->db->prepare("SELECT * FROM user WHERE user_name = ?");
        $query->execute([$user_name]);
    
        $user = $query->fetch(PDO::FETCH_OBJ);
    
        return $user;
    }
    
    
}