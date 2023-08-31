<?php 

namespace App\Controllers;

class ResponseController extends BaseController {

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
