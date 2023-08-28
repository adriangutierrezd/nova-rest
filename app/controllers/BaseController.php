<?php 

namespace App\Controllers;

class BaseController{


    public function getRequest(){
        header('Content-Type: application/json');
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
        return $decoded;
    }


    public function httpResponse($code, $message, $data){
        http_response_code($code);
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data
        ];
        echo json_encode($response);
        die();
    }

}