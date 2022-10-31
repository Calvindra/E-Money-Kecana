<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Usermarketplace;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Memarketplace extends ResourceController
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
        $model = new Usermarketplace();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
       // $model = $db -> table('users');
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
                'alamat' => $decoded->alamat,
               

            ];
    
            return $this->respond($response);
        } catch (\Throwable $th) {
            return $this->fail($th);
        }
    }
 
}