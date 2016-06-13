<?php

abstract class RestController {

    public $user = null;
    public $response = '';
    
    public function __construct($response) {
        $this->response = $response;
    }
    abstract function actionPermission();
    
    public function authenticate($username, $password) {
        $hashed_password = md5($password);
        $query = "SELECT * FROM users where name='".mysql_real_escape_string($username)."' and password='".$hashed_password."'";
        return mysql_fetch_assoc(mysql_query($query));        
    }
    
    public function authorize($username, $password) {
      $user = $this->authenticate($username, $password);
      if($user !== FALSE) {
        $this->user = $user;
      }
    }
    
    public function authorizeAction($action) {
        if  (   (array_key_exists($action, $this->actionPermission()))
                &&  
                (
                    ($this->actionPermission()[$action] === true && $this->user !== null)
                    || 
                    ($this->actionPermission()[$action] === false)
                )
            )
        {
            return true;
        }
        return false;
    }

}
