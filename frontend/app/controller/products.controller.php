<?php
require_once './app/model/products.model.php';
require_once './app/model/abstract.model.php';
require_once './app/view/products.view.php';
require_once './app/controller/error.controller.php';
require_once './app/controller/success.controller.php';

class ProductsController
{
    private $view;
    private $model;
    private $error;
   
    

    public function __construct($res)
    {
        $this->view = new ProductsView($res->user);
        $this->model = new ProductsModel();
        $this->error = new ErrorControler($res);
       
    }

    public function showCategories()
    {
        $products = $this->model->getProducts();
        $this->view->showProducts($products);
    }

    public function viewItemByCategories($id_product)
    {
        $productExists = $this->model->checkIDExists($id_product);
        if (!$productExists) {
            $error = "Esta categoría no existe";
            $redir = 'categorias';
            $this->error->showError($error, $redir);
        } else {
            $orders = $this->model->getOrdersByProductId($id_product);
            $product = $this->model->getProduct($id_product);
            if (count($orders) === 0) {
                $error = "No hay órdenes para este producto";
                $redir = "categorias";
                $this->error->showError($error, $redir);
            } else {
                $this->view->showOrdersById($orders, $product);
            }
        }
    }

    //ABM
    public function productsABM($result = null, $success = '')
    {
        $products = $this->model->getProducts();
        $this->view->seeABMProducts($products, $result, $success);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = $this->getValidatedProductData();

            // Si la validación falla, manejar el error
            if (!$productData) {
                $error = "Error: completar todos los campos obligatorios";
                $redir = "nuevoProducto";
                $this->error->showError($error, $redir);
            } else {
                $result = $this->model->insertProduct($productData['name'], $productData['price'], $productData['description'], $productData['image_product']);
                if($result)
                header('Location: ' . BASE_URL . 'realizado');
            else
                $this->error->showError('Error en la base de datos', 'controlarProductos');
            return;
            }
        } else {
            $this->view->addProduct();
        }
    }

    public function deleteProduct($id)
    {
        if($this->model->checkIDExists($id)){
            $result = $this->model->eraseProduct($id);
            if($result)
            header('Location: ' . BASE_URL . 'realizado');
        else
            $this->error->showError('Error en la base de datos', 'controlarProductos');
        return;
        } else {
            $error = "No existe el producto con el id=$id";
            $redir = "controlarProductos";
            $this->error->showError($error, $redir);
        }
    }

    public function updateProduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $product = $this->model->getProduct($id);
            if (!$product) {
                $error = "No existe el producto con el id=$id";
                $redir = "controlarProductos";
                $this->error->showError($error, $redir);
                return;
            }
            $this->view->showProductForm($product, true);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = $this->getValidatedProductData();
            if (!$productData) {
                $error = "Error: completar todos los campos obligatorios";
                $redir = "controlarProductos";
                $this->error->showError($error, $redir);
            } else {
                // Actualizo el producto
                $result = $this->model->updateProduct($id, $productData['name'], $productData['price'], $productData['description'], $productData['image_product']);
                if($result)
                header('Location: ' . BASE_URL . 'realizado');
            else
                $this->error->showError('Error en la base de datos', 'controlarProductos');
            return;
            }
        }
    }
    private function getValidatedProductData()
    {
        // Verificar campos obligatorios
        if (
            !isset($_POST['name']) || empty($_POST['name']) ||
            !isset($_POST['price']) || empty($_POST['price']) ||
            !isset($_POST['description']) || empty($_POST['description'])
        ) {
            return false;
        }
        $image_product = null;
        if (!empty($_POST['image_product'])) {
            $image_product = htmlspecialchars($_POST['image_product']);
            if (!filter_var($image_product, FILTER_VALIDATE_URL)) {
                return false;
            }
        }
        // Si todos los datos son válidos, devolver un array con los datos
        return [
            'name' => htmlspecialchars($_POST['name']),
            'price' => htmlspecialchars($_POST['price']),
            'description' => htmlspecialchars($_POST['description']),
            'image_product' => $image_product 
        ];
    }

    public function showProductForm($id = null)
    {
        if ($id != null) {
            if ($this->model->checkIDExists($id)) {
                $product = $this->model->getProduct($id);
                $this->view->showProductForm($product, true);
            } else {
                $error = "El producto no existe";
                $redir = "controlarOrdenes";
                $this->error->showError($error, $redir);
            }
        } else {
            $this->view->addProduct();
        }
    }
}
