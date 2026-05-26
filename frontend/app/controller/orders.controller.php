<!--Comentarios: para porder pasar el $res del error lo tuve que poner error en el constructor para que me funcionara-->
<?php
require_once './app/model/orders.model.php';
require_once './app/view/orders.view.php';
require_once './app/model/abstract.model.php';
require_once './app/model/products.model.php';
require_once './app/controller/error.controller.php';
require_once './app/controller/success.controller.php';
class OrdersControlers{
    private $view;
    private $model;
    private $error;
  
    public function __construct($res){
        $this->view = new OrdersView($res->user);
        $this->model = new OrdersModel();
        $this->error = new ErrorControler($res);
        
    }
    public function showHome(){
        $orders = $this->model->getOrders();
        $this->view->showOrders($orders);
    }
    public function viewOrder($id){
        if($this->model->checkIDExists($id)){
            $order = $this->model->getOrder($id);
            $id_product = $order->id_product;
            $controletProduct = new ProductsModel();
            if($controletProduct->checkIDExists($id_product)){
                $product = $controletProduct->getProduct($id_product);
                if($product != null){
                    $this->view->showOrder($order, $product);
                }
            }
            
        }else{
            $error="No existe la orden y/o producto";
            $redir="home";
            $this->error->showError($error,$redir);
        }
    }
    public function OrdersABM(){
        $ordens = $this->model->getOrders();
        $this->view->seeABMOrders($ordens);
    }
    public function deleteOrder($id){
        if($this->model->checkIDExists($id)){
            $result = $this->model->eraseOrder($id);
            if($result)
                header('Location: ' . BASE_URL . 'realizado');
            else
                $this->error->showError('Error en la base de datos', 'controlarOrdenes');
            return;

        }else{
            $error = "El producto no existe";
            $redir = "controlarOrdenes";
            $this->error->showError($error,$redir);
        }
    }
    public function showOrderForm($id = null){
        $modelProducts = new ProductsModel();
        $products = $modelProducts->getProducts();
        if($id != null){
            if($this->model->checkIDExists($id)){
                $order = $this->model->getOrder($id);
                $this->view->seeForm($order, $products);
            }else{
                $error = "El producto no existe";
                $redir = "controlarOrdenes";
                $this->error->showError($error,$redir);
            }
        }else{
            $this->view->seeForm(null, $products);
        }
        
    }
    private function checkFormData(){
        if(isset($_POST['product']) && !empty($_POST['product']) && isset($_POST['cant_products']) && $_POST['cant_products'] > 0 && !empty($_POST['cant_products']) && isset($_POST['date']) && !empty($_POST['date'])){
            $id_product = $_POST['product'];   
            $cant_products = $_POST['cant_products'];
            $date = $_POST['date'];
            $modelProducts = new ProductsModel();
            if($modelProducts->checkIDExists($id_product)){
                $product = $modelProducts->getProduct($id_product);
                $total = $product->price * $cant_products;
                $data = array(
                    "id_product"=>$id_product,
                    "cant_products"=>$cant_products,
                    "date"=>$date,
                    "total"=>$total
                );
                return $data;
            }else{
                $error = "El producto no existe";
                $redir = "controlarOrdenes";
                $this->error->showError($error,$redir);
                return;
            }
        }else{
            $error = "Faltan completar campos";
            $redir = "controlarOrdenes";
            $this->error->showError($error,$redir);
            return;
        }
    }
    public function updateOrder($id){
        if($this->model->checkIDExists($id)){
            $data = $this->checkFormData();
            if($data != null){
                $result = $this->model->updateOrder($id, $data);
                if($result)
                    header('Location: ' . BASE_URL . 'realizado');
                else
                    $this->error->showError('Error en la base de datos', 'controlarOrdenes');
                return;
            }
        }
            $error = "La orden no existe";
            $redir = "controlarOrdenes";
            $this->error->showError($error,$redir);
      
    }
    public function createOrder(){
        $data = $this->checkFormData();
        if($data != null){
            $result = $this->model->createOrder($data);
        }else{
            $result = null;
        }
        if($result)
            header('Location: ' . BASE_URL . 'realizado');
        else
            $this->error->showError('Error en la base de datos', 'controlarOrdenes');
                
        return;
    }
    
    
}