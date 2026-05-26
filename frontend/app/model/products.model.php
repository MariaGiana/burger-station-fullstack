<?php
require_once './config.php';
require_once './app/model/abstract.model.php';
class ProductsModel extends modelAbstract{
    public function __construct(){
        parent::__construct();    
    }
    public function getProducts() {
        $query = $this->db->prepare("SELECT * FROM product");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getProduct($id) {
        $query = $this->db->prepare("SELECT * FROM product WHERE id = ?");
        $result = $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function checkIDExists($id_product){
        $query = $this->db->prepare("SELECT * FROM product WHERE id = ?");
        $result = $query->execute([$id_product]);
        return $query->fetchColumn() > 0;
    }

    public function getOrdersByProductId($id_product){
        $query = $this->db->prepare('SELECT * FROM orders WHERE id_product = ?');
        $query->execute([$id_product]);
        $orders = $query->fetchAll(PDO::FETCH_OBJ);
        return $orders;
    }

    //CRUD
    public function insertProduct($name, $price, $description,$image_product){
        $query = $this->db->prepare('INSERT INTO product(name,price,description,image_product) VALUES (?, ?, ?, ?)');
        $query->execute([$name,$price, $description, $image_product]);
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function eraseProduct($id){
        $query = $this->db->prepare('DELETE FROM product WHERE id = ?');
        $result = $query->execute([$id]);
        return $result;
    }

    public function updateProduct($id, $name, $price, $description, $image_product) {
        $query = $this->db->prepare('UPDATE product SET name = ?, price = ?, description = ?, image_product = ? WHERE id = ?');
        $query->execute([$name, $price, $description,$image_product,$id]);
                return true; 
     } 


     
    
    
}

