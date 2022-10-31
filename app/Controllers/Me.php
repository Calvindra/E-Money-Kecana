<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Me extends ResourceController
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
        $db = \Config\Database::connect();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
        $model = $db -> table('users');
        $token = explode(' ', $header)[1];
        
            $th = [
               'status' => 400,
               'message'   => [
                  'error'  => "Token anda salah"
               ]
            ];
            
         
        try {
            $decoded = JWT::decode($token, new Key ($key, "HS256"));
            $response = [
                'id' => $decoded->uid,
                'email' => $decoded->email,
                'nohp' => $decoded->nohp,
                'saldo' => ($model->getWhere(['id'=> $decoded->uid])->getResult()[0])->saldo
            ];
    
            return $this->respond($response);
        } catch (\Throwable $th) {
            return $this->fail($th);
        }
    }
 
}