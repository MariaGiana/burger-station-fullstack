<?php
class ReviewsModel extends modelAbstract
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getReviews($orderBy, $order, $name = null, $score = null, $coment = null, $reply = null)
    {
        $sql = "SELECT * FROM review";
        $params = [];
        $conditions = [];  
        
        
        if ($name != null) {
            $conditions[] = 'client_name LIKE ?';
            $params[] = "%" . $name . "%";  
        }
        if ($score != null) {
            $conditions[] = 'score = ?';
            $params[] = $score;
        }
        if ($coment != null) {
            $conditions[] = 'coment LIKE ?';
            $params[] = "%" . $coment . "%";
        }
        if ($reply != null) {
            $conditions[] = 'reply LIKE ?';
            $params[] = "%" . $reply . "%";
        }
        
        if (count($conditions) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        if ($orderBy) {
            switch ($orderBy) {
                case "name":
                    $sql .= " ORDER BY client_name";
                    break;
                case "score":
                    $sql .= " ORDER BY score";
                    break;
                case "id_product":
                    $sql .= " ORDER BY id_product";
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
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;        
    }
    public function checkIDExists($id){
        $query = $this->db->prepare("SELECT * FROM review WHERE id = ?");
        $query->execute([$id]);
        return $query->fetchColumn() > 0;
    }
    public function updateReview($id, $data, $reply){
        $query = $this->db->prepare("UPDATE review SET id_product = ?, client_name = ?, score = ?, coment = ?, reply = ? WHERE review . id = ?");
        $result = $query->execute([$data['id_product'], $data['client_name'], $data['score'], $data['coment'], $reply, intval($id)]);
        return $result;
    }
    public function updateReplyReview($id, $reply){
        $query = $this->db->prepare("UPDATE review SET reply = ? WHERE review . id = ?");
        $result = $query->execute([$reply, intval($id)]);
        return $result;
    }
    public function getReview($id) {    
        $query = $this->db->prepare('SELECT * FROM review WHERE id = ?');
        $query->execute([$id]);   
        $review = $query->fetch(PDO::FETCH_OBJ);
        return $review;
    }
    public function createReview($data,$reply){
        $query = $this->db->prepare('INSERT INTO review(id_product, client_name, score, coment) VALUES (?, ?, ?, ?)');
        $query->execute([$data['id_product'], $data['client_name'], $data['score'], $data['coment']]);
        $id = $this->db->lastInsertId();
        return $id;
    }
    public function eraseReview($id){
        $query = $this->db->prepare("DELETE FROM review WHERE id = ?");
        $result = $query->execute([$id]);
        return $result;
    }
   
}
