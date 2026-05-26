<?php
require_once './app/model/orders.model.php';
require_once './app/view/view.php';
require_once './app/model/abstract.model.php';
require_once './app/model/products.model.php';
class OrdersControlers{
    private $view;
    private $model;
    public function __construct(){
        $this->view = new view();
        $this->model = new OrdersModel();
    }
    public function getOrders($req, $res){
        $orderBy = false;
        $order = 'asc';
        $orderValues = ['cant_products', 'total','date','id_product'];
        $filterValues = ['cant_products', 'total_greater','total_minor','total','date', 'orderBy', 'resource', 'page', 'show', 'order', 'id_product'];
        $id_product =null;
        $total = null;
        $cant_products = null;
        $date = null;
        $total_greater = null;
        $total_minor = null;
        if(isset($req->query->id_product)){
            $id_product = $req->query->id_product;
        }
        if(isset($req->query->total)){
            $total = $req->query->total;
        }
        if(isset($req->query->cant_products)){
            $cant_products = $req->query->cant_products;
        }
        if(isset($req->query->date)){
            $date = $req->query->date;
        }
        if(isset($req->query->total_greater)){
            $total_greater = $req->query->total_greater;
        }
        if(isset($req->query->total_minor)){
            $total_minor = $req->query->total_minor;
        }
        if(isset($req->query->orderBy)){
            $orderBy = $req->query->orderBy;
            if(!in_array($orderBy, $orderValues)){
                return $this->view->showResult("No se puede ordenar por el campo ingresado", 400);
            }
        }
        if(isset($req->query->order)){
            $order = $req->query->order;
            if($order !== 'asc' && $order !== 'desc'){
                return $this->view->showResult("No se puede ordenar de esa forma, ingrese asc o desc", 400);
            }
        }
        foreach ($req->query as $key => $value) {
            if (!in_array($key, $filterValues)) {
                return $this->view->showResult("El parametro '$key' no es válido. Error de sintaxis", 400);
            }
        }
        $orders = $this->model->getOrders($orderBy, $order, $total,$cant_products,$date,$total_greater,$total_minor, $id_product);
        if(!$orders){
            return $this->view->showResult("Las ordenes no se pudieron conseguir", 404);
        }
        //paginacion desde php
        if(isset($req->query->show) && !empty($req->query->show) && isset($req->query->page) && !empty($req->query->page)){
            $lim= count($orders);
            $see = $req->query->show;
            if($see <= 0 ){
                return $this->view->showResult("Ingrese numeros validos show > 0 y show <= $lim ", 400);
            }
            $pag = $req->query->page;
            if(($lim/$see) <= 1 && intval($pag)!==1){
                return $this->view->showResult("Unico numero valido para page 1", 400);
            }
            if($see <= 1 && intval($pag > $lim)){
                return $this->view->showResult("Ingrese numeros para page entre 1 y $lim ", 400);
            }
            if($pag <= 0 || $pag > ceil($lim/$see)){
                return $this->view->showResult("Ingrese numeros validos page > 0 y page <= " . ceil(($lim/$see)) , 400);
            }
            $num = $see * $pag;    
            for ($i=$num-$see; $i < $num && $i < $lim ; $i++) { 
               $pageOrders[] = $orders[$i];
            }
            return $this->view->showResult($pageOrders, 200);
        }
        return $this->view->showResult($orders, 200);
    }
    public function getOrder($req, $res){
        $id= $req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("La orden con id=".$id." no existe", 404);
        }
        $order = $this->model->getOrder($id);
        return  $this->view->showResult($order, 200);
       
    }
    public function deleteOrder($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $id =$req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("La orden con id=".$id." no existe", 404);
        }
        $result = $this->model->eraseOrder($id);
        if($result)
            return $this->view->showResult("La orden con id=".$id." se elimino con exito", 200);
        else
            return $this->view->showResult("La orden con id=".$id." no se pudo eliminar", 500);
    }
    private function checkFormData($req){
        if(empty($req->body->id_product) || empty($req->body->cant_products) || empty($req->body->date)){
            return $this->view->showResult("Faltan completar campos", 400);
        }
        $id_product = $req->body->id_product;   
        $modelProducts = new ProductsModel();
        if(!$modelProducts->checkIDExists($id_product)){
            return $this->view->showResult("El id=".$id_product." del producto no existe", 404);
        }
        $cant_products = $req->body->cant_products;
        if($cant_products <=0){
            return $this->view->showResult("ingrese datos validos", 400);
        }
        $date = $req->body->date;
        $product = $modelProducts->getProduct($id_product);
        $total = $product->price * $cant_products;
        $data = array(
            "id_product"=>$id_product,
            "cant_products"=>$cant_products,
            "date"=>$date,
            "total"=>$total
        );
        return $data;
    }
    public function updateOrder($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $id= $req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("La orden con id=".$id." no existe", 404);
        }
        $data = $this->checkFormData($req);
        if($data === null){
            return;
        }
        $result = $this->model->updateOrder($id, $data);
        if(!$result){
            return $this->view->showResult("Ocurrio un error al actualizar la orden con id= $id ", 500);
        }
        $order = $this->model->getOrder($id);
        if(!$order){
            return $this->view->showResult("Error al traer la orden actualizada", 404);
        }
        return $this->view->showResult($order,200);
    }
    public function createOrder($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $data = $this->checkFormData($req);
        if($data === null){
            return;
        }
        $last_id = $this->model->createOrder($data);
        if(!$last_id){
            return $this->view->showResult("La orden no se pudo crear", 500);
        }
        $order = $this->model->getOrder($last_id);
        if(!$order){
            return $this->view->showResult("Error al traer la orden creada", 404);
        }
        return  $this->view->showResult($order, 201);
    }
}