<?php 

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;
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
            $this->httpResponse(HTTP_CODE_OK, 'Token generated', ['token' => $jwt, 'expires' => $_ENV['JWT_TTL']]);
        }catch (UnexpectedValueException $e) {
            $this->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error generating token', ['error' => $e->getMessage()]);
        }catch(\Exception $e){
            $this->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error generating token', ['error' => $e->getMessage()]);
        }
    }

}