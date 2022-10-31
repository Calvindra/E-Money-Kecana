<?php
 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use App\Models\Itemterjual;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Lihatpengiriman extends ResourceController
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
            'id' => 'required|is_int',
            'nohppembeli' => 'required|is_string'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $db = \Config\Database::connect();
        $model = new Itemterjual();
        $user = $model->where("id", $this->request->getVar('id'))->first();
        if(!$user) return $this->failNotFound('id tidak ditemukan');

        $user = $model->where("nohppembeli", $this->request->getVar('nohppembeli'))->first();
        if(!$user) return $this->failNotFound('nohppembeli tidak ditemukan');
 

        $itemsaya =  $model->getWhere(['id'=> $this->request->getVar('id')]);
        $item = $itemsaya->getResult()[0];
        $item2 = $itemsaya->getResult()[0]->nohppembeli;
        
        if( $decoded->nohp == $this->request->getJsonVar('nohppembeli')){
            if( $item2 == $this->request->getJsonVar('nohppembeli')){
        $data = [
            'id' =>  $item->id,
            'merk' =>  $item->merk,
            'nohp' =>  $item->nohp,
            'nohppembeli' =>  $item->nohppembeli,
            'alamatpengiriman' =>  $item->alamatpengiriman,
        ];
        
     
        return $this->respond($data);
        echo "\n";
        
        $response = [
            'status' => 200,
            'message'   => [
               'success'  => "Tunggu ya, barang anda masih dikirim"
            ]
         ];
         return $this->respond($response);
         
    }
    else
    {
        $response = [
            'status' => 400,
            'message'   => [
               'error'  => "id tidak sesuai dengan nomer hp anda"
            ]
         ];
         return $this->respond($response);
   } 
}
    else
    {
        $response = [
            'status' => 400,
            'message'   => [
               'error'  => "Akses ditolak"
            ]
         ];
         return $this->respond($response);
   } 
}
}