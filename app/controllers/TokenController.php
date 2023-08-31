<?php 

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;
use Dotenv;
use Exception;
use stdClass;

require_once __DIR__.'/../config/constants.php';

class TokenController {

    private $dotenv;
    private $responseController;
    private $requestController;

    public function __construct(){
        $this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $this->dotenv->load();

        $this->responseController = new ResponseController();
        $this->requestController = new RequestController();
    }

    public function generateToken(){
        try {
            $request = $this->requestController->getRequest();
            $request['iat'] = time();
            $request['exp'] = time() + $_ENV['JWT_TTL'];

            $jwt = JWT::encode($request, $_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']);
            
             $this->responseController->httpResponse(HTTP_CODE_OK, 'Token generated', ['token' => $jwt, 'expiresIn' => $_ENV['JWT_TTL']]);
        } catch (UnexpectedValueException $e) {
             $this->responseController->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error generating token', ['error' => $e->getMessage()]);
        } catch(Exception $e) {
             $this->responseController->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error generating token', ['error' => $e->getMessage()]);
        }
    }


    public function validateToken(){
        $request = $this->requestController->getRequest();
        $token = $this->requestController->getBearerToken();

        try {
            $headers = new stdClass();
            $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], $_ENV['JWT_ALGORITHM']), $headers);

            if(!property_exists($decoded, 'iat')) throw new Exception('Missing iat property');
            if(!property_exists($decoded, 'exp')) throw new Exception('Missing exp property');
            if(time() > $decoded->exp) throw new Exception('Token expired');

            $this->responseController->httpResponse(HTTP_CODE_OK, 'Token validated', ['token' => $decoded]);
        } catch (UnexpectedValueException $e) {
             $this->responseController->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error validating token', ['error' => $e->getMessage()]);
        } catch(Exception $e) {
             $this->responseController->httpResponse(HTTP_CODE_INTERNAL_SERVER_ERROR, 'Error validating token', ['error' => $e->getMessage()]);
        }
    }


}
