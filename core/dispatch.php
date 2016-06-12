<?php 

class Dispatch {

    public $reuqest_type = '';
    public $dispatch_url = '';
    public $response = '';
    public $username = '';
    private $password = '';
    
    public function __construct($request_type, $dispatch, $response, $username, $password) {
        $this->request_type = $request_type;
        $this->dispatch_url = $dispatch;
        $this->response = $response;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function handle() {
        $dispatch_url_components = explode("/", $this->dispatch_url);
        $controllerName = $dispatch_url_components[0];
        $actionName = $dispatch_url_components[1];
        
        if(file_exists('app/' . $controllerName . 'Controller.php')) {
            require_once 'app/' . $controllerName . 'Controller.php';
            
            $cname = $controllerName. 'Controller';
            $controller = new $cname($this->response);
            $action = strtolower($this->request_type) . '' . ucfirst($actionName);
            if(method_exists($controller, $action)) {
                $controller->authorize($this->username, $this->password);
                
                if($controller->authorizeAction($action) === true) {
                   $controller->$action(); 
                }
                else {
                    $this->response->setStatus(403);
                    $this->response->setMessage("Unauthorised User");
                    $this->response->send();
                }
            }
            else {
                $this->response->setStatus(404);
                $this->response->setMessage("Action Not Found");
                $this->response->send();
            }
            
        }
        else {
            $this->response->setStatus(404);
            $this->response->setMessage("Controller Not Found");
            $this->response->send();
        }
    }

}