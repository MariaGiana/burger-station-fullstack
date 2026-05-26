<?php
require_once './app/model/abstract.model.php';
require_once './config.php';
class OrdersModel extends modelAbstract{
    public function __construct(){
        parent::__construct();   
    }
    public function getOrders($orderBy, $order, $total,$cant_products,$date,$total_greater,$total_minor,$id_product){
        $sql = "SELECT * FROM orders";
        $params = [];
        $conditions = []; 
        if ($total != null) {
            $conditions[] = 'total = ?';
            $params[] = $total;
        }
        if ($cant_products != null) {
            $conditions[] = 'cant_products = ?';
            $params[] = $cant_products;
        }
        if ($date != null) {
            $conditions[] = 'date = ?';
            $params[] = $date;
        }
        if ($total_greater != null) {
            $conditions[] = 'total > ?';
            $params[] = $total_greater;
        }
        if ($total_minor != null) {
            $conditions[] = 'total < ?';
            $params[] = $total_minor;
        }
        if ($id_product != null) {
            $conditions[] = 'id_product = ?';
            $params[] = $id_product;
        }
        if (count($conditions) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        if($orderBy){
            switch($orderBy){
                case "date":
                    $sql .= " ORDER BY date";
                    break;
                case "total":
                    $sql .= " ORDER BY total";
                    break;
                case "cant_products":
                    $sql .= " ORDER BY cant_products";
                    break;
                case "id_product":
                    $sql .=  " ORDER BY id_product" ;
                    break;  
            }
        }else{
            $sql .= " ORDER BY id";
        }
        if($order === 'desc'){
            $sql .= " DESC";
        }else if($order === 'asc'){
            $sql .= " ASC";
        }
        $query = $this->db->prepare($sql);
        $query->execute($params);
        $orders = $query->fetchAll(PDO::FETCH_OBJ);
        return $orders;
    }
    public function getOrder($id){
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $result = $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public function checkIDExists($id){
        $query = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $query->execute([$id]);
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
        $query->execute([$data["id_product"], $data["cant_products"],  $data["total"], $data["date"]]);
        $id = $this->db->lastInsertId();
        return $id;
    }
    public function countOrders(){
        $query = $this->db->prepare("SELECT COUNT(*) AS total_records FROM orders");
        $result = $query->execute();
        $query = $query->fetch(PDO::FETCH_OBJ);
        return $query->total_records;
    }
}

