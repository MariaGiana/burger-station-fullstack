<?php 
class OrdersView{
    public $user = null;
    public function __construct($res){
        if($res != null)
            $this->user = $res->user_name;
       
    }
    public function showOrders($orders){
        require_once './templates/listOrders.phtml';
    }

    public function showOrder($order, $product){
        require_once './templates/viewOrder.phtml';
    }

    public function seeABMOrders($ordens){
        require_once './templates/CRUDorder.phtml';
        
    }
    public function seeForm($order, $products){
        require_once './templates/forms/formCRUDorder.phtml';

    }
}

