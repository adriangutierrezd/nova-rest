<?php 

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv;
use Exception;

class TokenController extends BaseController{

    private $dotenv;

    public function __construct(){
        $this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $this->dotenv->load();
    }

    public function generateToken(){
        $request = $this->getRequest();
        try{
            $request['iat'] = time();
            $jwt = JWT::encode($request, $_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']);
            $this->httpResponse(200, 'Token generated', ['token' => $jwt, 'expires' => $_ENV['JWT_EXPIRES_IN']]);
        }catch(\Exception $e){
            $this->httpResponse(500, 'Error generating token', ['error' => $e->getMessage()]);
        }
    }

}