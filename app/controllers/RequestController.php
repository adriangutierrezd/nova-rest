<?php 

namespace App\Controllers;

class RequestController extends BaseController {

    public function normalizeRequest($request){
        $request = is_object($request) ? (array) $request : $request;
        $request = array_map(function($value){
            return trim(strtolower($value));
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

    public function getRequest($responseType = self::JSON_RESPONSE_TYPE){
        if(!in_array($responseType, self::AVAILABLE_RESPONSE_TYPES, true)) {
            throw new Exception(self::INVALID_RESPONSE_TYPE);
        }

        header('Content-Type: '.self::CONTENT_TYPES[$responseType]);
        $content = trim(file_get_contents("php://input"));
        return json_decode($content, true);
    }

    public function getBearerToken(){
        $headers = getallheaders();
        $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if(is_null($authorization)){
            throw new Exception('Missing authorization header');
        }

        $tokenData = explode(' ', $authorization);
        if(count($tokenData) !== 2 || $tokenData[0] !== 'Bearer'){
            throw new Exception('Invalid authorization header');
        }

        return $tokenData[1];
    }

    public function validateDataTypes($request, $expectedTypes){

        if(!is_array($request)){
            throw new Exception('Invalid request data, Array expected but '.gettype($request).' found');
        }

        if(!is_array($expectedTypes)){
            throw new Exception('Invalid expected types, Array expected but '.gettype($expectedTypes).' found');
        }

        $errors = [];
        foreach ($expectedTypes as $field => $expectedType) {

            if(!isset($request[$field])){
                $errors[] = "Field '$field' is missing.";
                continue;
            }

            $actualType = gettype($request[$field]);
            if ($actualType !== $expectedType) {
                $errors[] = "Field '$field' expected to be $expectedType, but found $actualType.";
            }
        }

        return $errors;
    }

}
