<?php

class ProductController extends RestController {
    public function actionPermission() {
        return array(
            'getList' => false,
            'getSearch' => false,
            'getGet' => true,
            'postAdd' => true,
            'putUpdate' => true,
            'deleteDelete' => true,
        );
    }
    
    public function getList() {
        $result = mysql_query("SELECT * FROM products");
        $products = array();
        while ($res = mysql_fetch_assoc($result)) {
            $products[] = $res;
        }
        $this->response->setStatus(200);
        $this->response->setMessage("Success");
        $this->response->setData($products);
        $this->response->send();
    }
    
    public function getGet() {
        $product_id = (int) $_GET['product_id'];
        $sql = "SELECT * FROM products WHERE product_id=$product_id";
        $product = mysql_fetch_assoc(mysql_query($sql)); 
        $this->response->setStatus(200);
        if($product !== false) {
            $this->response->setMessage("Success");
            $this->response->setData($product);
        }
        else {
            $this->response->setMessage("invalid product id");
        }
        $this->response->send();
    }
    
    public function getSearch() {
        $options = array();
        if(isset($_GET['name'])) {
           $options['name'] = $_GET['name'];  
        }
        if(isset($_GET['sku'])) {
           $options['sku'] = $_GET['sku'];  
        }
        
        $where = array();
        foreach ($options as $key => $val) {
            if($key == 'name') {
                $where[] = "name like '%" . mysql_real_escape_string($val) . "%'";
            }
            if($key == 'sku') {
                $where[] = "SKU = '" . mysql_real_escape_string($val) . "'";
            }
        }
        $where = implode (" AND ", $where);
        
        
        $this->response->setStatus(200);
        $this->response->setMessage("Success");
        if($where != '') {
            $result = mysql_query("SELECT * FROM products WHERE $where");
            $products = array();
            while ($res = mysql_fetch_assoc($result)) {
                $products[] = $res;
            }
            $this->response->setData($products);
        }
        $this->response->send();
    }
    
    public function postAdd() {
        $product = json_decode(file_get_contents("php://input"));
        $valid = $this->validateProduct($product);
        if($valid['status'] == true) {
            mysql_query("INSERT INTO products SET name='" . mysql_real_escape_string($product->name) . "', 
                    description='" . mysql_real_escape_string($product->description) . "', 
                    SKU='" . mysql_real_escape_string($product->sku) . "',
                    price='" . mysql_real_escape_string($product->price) . "'");
            $this->response->setStatus(200);
            $this->response->setMessage("product added");
        }
        else {
            $this->response->setStatus(200);
            $this->response->setMessage("invalid Product data");
        }
        $this->response->send();
    }
    
    private function validateProduct($product) {
        $return['status'] = true;
        if(!isset($product->name)) {
            $return['status'] = false;
        }
        if(!isset($product->description)) {
            $return['status'] = false;
        }
        if(!isset($product->sku)) {
            $return['status'] = false;
        }
        if(!isset($product->price)) {
            $return['status'] = false;
        }
        
        return $return;
    }
    
    public function putUpdate() {
        $product_id = (int) $_GET['product_id'];
        $product = json_decode(file_get_contents("php://input"));
        $valid = $this->validateProduct($product);
        if($valid['status'] == true) {
            mysql_query("update products SET name='" . mysql_real_escape_string($product->name) . "', 
                    description='" . mysql_real_escape_string($product->description) . "', 
                    SKU='" . mysql_real_escape_string($product->sku) . "',
                    price='" . mysql_real_escape_string($product->price) . "' WHERE product_id=$product_id");
            $this->response->setStatus(200);
            if(mysql_affected_rows() > 0) {
                $this->response->setMessage("product updated");
            }
            else {
                $this->response->setMessage("invalid product id");
            }
        }
        else {
            $this->response->setStatus(200);
            $this->response->setMessage("invalid Product data");
        }
        $this->response->send();
    }
    
    public function deleteDelete() {
        $product = json_decode(file_get_contents("php://input"));
        mysql_query("DELETE FROM products WHERE product_id=". mysql_real_escape_string($product->product_id));
        $this->response->setStatus(200);
        if(mysql_affected_rows() > 0) {
            $this->response->setMessage("product deleted");
        }
        else {
            $this->response->setMessage("invalid product id");
        }
        $this->response->send();
    }
}