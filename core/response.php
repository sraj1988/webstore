<?php

class Response {
    
    public $output = '';
    public $response_format = 'json';
    
    private $status = 200;
    private $message = '';
    private $data = array();
        
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function setMessage($msg) {
        $this->message = $msg;
    }
    
    public function setData($data) {
        $this->data = $data;
    }
    
    public function send() {
        $formatter_function_name = 'send' . ucfirst($this->response_format). 'Response';
        
        $this->output['status'] = $this->status;
        $this->output['message'] = $this->message;
        $this->output['data'] = $this->data;
        $this->$formatter_function_name($this->output);
    }
    
    public function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}