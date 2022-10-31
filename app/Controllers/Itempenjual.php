<?php
 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Itempenjual extends ResourceController
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
        helper(['form']);
        $rules = [
            'id' => 'required|is_int'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $db = \Config\Database::connect();
        $model = new Itemmobil();
        $model2 = new Usermarketplace();
        $user = $model->where("id", $this->request->getVar('id'))->first();
        if(!$user) return $this->failNotFound('id tidak ditemukan');
 
       

        $itemsaya =  $model->getWhere(['id'=> $this->request->getVar('id')]);
        $item = $itemsaya->getResult()[0];
        $item2 = $itemsaya->getResult()[0]->nohp;
        
      
        if($decoded->nohp == $item2){
        $data = [
            'id' =>  $item->id,
            'merk' =>  $item->merk,
            'status' =>  $item->status,
        ];
     
        return $this->respond($data);
        echo "\n";
        
        $response = [
            'status' => 200,
            'message'   => [
               'success'  => "Jangan lupa konfirmasi setelah dibayar, dan tunggu jika belum dibayar"
            ]
         ];
         return $this->respond($response);
        }
        else{
             $response = [
                'status' => 400,
                'message'   => [
                   'error'  => "Akses ditolak karena tidak sesuai"
                ]
             ];
             return $this->respond($response);
        }
    }
}