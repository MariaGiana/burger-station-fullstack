<?php
class ProductsView{
    public $user = null;

    public function __construct($res) {
        if($res != null)
        $this->user = $res->user_name;
    }
    public function showProducts($products){
        require_once './templates/listProducts.phtml';
    }

    public function showOrdersById($orders, $product)
    {
        require_once './templates/viewItems.phtml';
    }

    public function seeABMProducts($products)
    {
        require_once './templates/CRUDproduct.phtml';
    }

    public function addProduct()
    {
        require_once './templates/forms/formCRUDproduct.phtml';
    }

    public function showProductForm($product = null, $isEdit = false)
    {
        require_once './templates/forms/formCRUDproduct.phtml';
    }

}
