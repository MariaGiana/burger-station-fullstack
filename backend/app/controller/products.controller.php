<?php
require_once './app/model/products.model.php';
require_once './app/model/abstract.model.php';
require_once './app/view/view.php';
class ProductsController
{
    private $view;
    private $model;
    public function __construct()
    {
        $this->view = new view();
        $this->model = new ProductsModel();
    }

    public function getProducts($req, $res){
        $orderBy = false;
        $order = 'asc';
        $orderValues = ['name','price','id'];
        $filterValues= ['name', 'price', 'description', 'img', 'resource', 'orderBy', 'page', 'show', 'order'];
        $name= null;
        $price = null;
        $description = null;
        $img = null;
        $page=1;
        $show=100;
        
        
            if (isset($req->query->name) && in_array('name', $filterValues)) {
                $name= $req->query->name;
            }
    
            if (isset($req->query->price)&& in_array('price', $filterValues)) {
                $price = $req->query->price;
            }
    
            if (isset($req->query->$description)&& in_array('description', $filterValues)) {
                $description = $req->query->$description;
            }
    
            if (isset($req->query->img)&& in_array('img', $filterValues)) {
                $img = $req->query->img;
            }

            foreach ($req->query as $key => $value) {
                if (!in_array($key, $filterValues)) {
                    return $this->view->showResult("El filtro '$key' no es válido. Error de sintaxis", 400);
                }
            }

            if(isset($req->query->orderBy)){
                $orderBy = $req->query->orderBy;
                if(!in_array($orderBy, $orderValues)){
                    return $this->view->showResult("No se puede ordenar por el campo ingresado. Error de sintaxis", 400);
                }
            }
            if(isset($req->query->order)){
                $order = $req->query->order;
                if($order !== 'asc' && $order !== 'desc'){
                    return $this->view->showResult("No se puede ordenar de esa forma, ingrese asc o desc", 400);
                }
            }
            if (isset($req->query->page)) {
                $page = $req->query->page; 
            }
            if (isset($req->query->show)) {
                $show = $req->query->show; 
            }
            
            $offset = ($page - 1) * $show;
            try {  
            $products = $this->model->getProducts($orderBy,$order, $name, $price, $description, $img, $show, $offset);
            if(!$products){
            return $this->view->showResult("Ningun producto coincide con lo buscado", 404);
        }
        return $this->view->showResult($products, 200);
    }   catch (Exception $e) {
        return $this->view->showResult("Error al buscar los productos", 500);
    }

    }
    public function getProduct($req, $res){
        $id= $req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("El producto con id=".$id." no existe", 404);
        }
        $product = $this->model->getProduct($id);
        return  $this->view->showResult($product, 200);
       
    }

    public function deleteProduct($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $id =$req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("El producto con id=".$id." no existe", 404);
        }
        $result = $this->model->eraseProduct($id);
        if($result)
            return $this->view->showResult("El producto con id=".$id." se elimino con exito", 200);
        else
            return $this->view->showResult("El producto con id=".$id." no se pudo eliminar", 500);
    }

    public function createProduct($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $data = $this->getValidatedProductData($req);
        if($data === null){
            return;
        }
        $last_id = $this->model->createProduct($data);
        if(!$last_id){
            return $this->view->showResult("El producto no se pudo crear", 500);
        }
        $product = $this->model->getProduct($last_id);
        return  $this->view->showResult($product, 201);
    }

    
    public function updateProduct($req, $res){
        if(!$res->user) {
            return $this->view->showResult("No autorizado", 401);
        }
        $id= $req->params->id;
        if(!$this->model->checkIDExists($id)){
            return $this->view->showResult("El producto con id=".$id." no existe", 404);
        }
        $productData = $this->getValidatedProductData($req);
        if($productData=== null){
            return;
        }
        $result = $this->model->updateProduct($id, $productData);
        $order = $this->model->getProduct($id);
        return $this->view->showResult($order,200);
    }

    private function getValidatedProductData($req)
    {
        // Verificar campos obligatorios
        if (empty($req->body->name) || empty($req->body->price) || empty($req->body->description) ) {
                return $this->view->showResult("Faltan completar campos", 400);
        }
        $name=$req->body->name;
        $price=$req->body->price;
        $description=$req->body->description;
        $image_product = null;
        if (isset($req->body->image_product) && $req->body->image_product !== '') {
            $image_product = htmlspecialchars($req->body->image_product);
        }

        $data= [
            'name' => htmlspecialchars($name),
            'price' => htmlspecialchars($price),
            'description' => htmlspecialchars($description),
            'image_product' => $image_product
        ];
        return $data;
    }

}
