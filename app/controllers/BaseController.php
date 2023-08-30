<?php 

namespace App\Controllers;

require_once __DIR__.'/../config/constants.php';

class BaseController{

    const JSON_RESPONSE_TYPE = 'JSON';
    const TEXT_RESPONSE_TYPE = 'TEXT';
    const AVAILABLE_RESPONSE_TYPES = [
        self::JSON_RESPONSE_TYPE,
        self::TEXT_RESPONSE_TYPE
    ];

    const CONTENT_TYPES = [
        self::JSON_RESPONSE_TYPE => 'application/json',
        self::TEXT_RESPONSE_TYPE => 'text/plain'
    ];

    const INVALID_RESPONSE_TYPE = 'Invalid response type';


    public function getRequest($responseType = self::JSON_RESPONSE_TYPE){

        if(!in_array($responseType, self::AVAILABLE_RESPONSE_TYPES, true)) throw new Exception(self::INVALID_RESPONSE_TYPE);

        header('Content-Type: '.self::CONTENT_TYPES[$responseType]);
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        return $responseType === self::JSON_RESPONSE_TYPE ? $this->normalizeRequest($decoded) : $decoded;
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

    public function normalizeRequest($request){
        $request = is_object($request) ? (array) $request : $request;
        $request = array_map(function($value){
            return trim((strtolower($value)));
        }, $request);
        return $request;
    }


    public function ensureRequiredFields($request, $requiredFields){

        $request = is_object($request) ? (array) $request : $request;
        $missing = array_filter($requiredFields, function($field) use ($request){
            return !isset($request[strtolower($field)]);
        });

        return $missing;

    }

}