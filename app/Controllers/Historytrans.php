<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Historytrans extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        $key = ("sdkfjasoduihaweotih2034890491230498");
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];
      
        try {
            $decoded = JWT::decode($token, new Key ($key, "HS256"));
            
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
    }
 
}