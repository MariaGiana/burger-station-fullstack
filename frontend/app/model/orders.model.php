<?php
require_once './app/model/abstract.model.php';
require_once './config.php';
class OrdersModel extends modelAbstract{
    public function __construct(){
        parent::__construct();   
    }
    public function getOrders(){
        $query = $this->db->prepare("SELECT * FROM orders");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getOrder($id){
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $result = $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public function checkIDExists($id){
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $result = $query->execute([$id]);
        return $query->fetchColumn() > 0;
    }
    
    public function updateOrder($id, $data){
        $query = $this->db->prepare("UPDATE orders SET id_product = ?, cant_products = ?, total = ?, date = ? WHERE  orders . id = ?");
        $result = $query->execute([$data["id_product"], $data["cant_products"],  $data["total"], $data["date"], $id]);
        return $result;
    }
    public function eraseOrder($id){
        $query = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        $result = $query->execute([$id]);
        return $result;
    }
    public function createOrder($data){
        $query = $this->db->prepare("INSERT INTO orders (id_product, cant_products, total, date) VALUES (?, ?, ?, ?)");
        $result = $query->execute([$data["id_product"], $data["cant_products"],  $data["total"], $data["date"]]);
        return $result;
    }

    
    
}

