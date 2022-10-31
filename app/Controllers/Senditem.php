<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use App\Models\Itemterjual;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Senditem extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        $db = \Config\Database::connect();
        $model = new Itemterjual();
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
            'merk' => 'required|is_string',
            'nohp' => 'required|is_string',
            'nohppembeli' => 'required|is_string',
            'alamatpengiriman' => 'required|is_string',
        ];
        
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model2 = new Usermarketplace();
        $user = $model2->where("nohp", $this->request->getVar('nohp'))->first();
        if(!$user) return $this->failNotFound('nohp tidak ditemukan');

        $model3 = new itemmobil();
        $user = $model3->where("merk", $this->request->getVar('merk'))->first();
        if(!$user) return $this->failNotFound('merk tidak ditemukan');

        //exit;
        if( $decoded->nohp == $this->request->getJsonVar('nohp')){
        $data = [
            'merk'     => $this->request->getVar('merk'),
            'nohp'     => $this->request->getVar('nohp'),
            'nohppembeli'     => $this->request->getVar('nohppembeli'),
            'alamatpengiriman'     => $this->request->getVar('alamatpengiriman'),
    
        ];
        
        
        
        $registered = $model->save($data);
     
        
        $this->respondCreated($registered);
 
        $response = [
            'status' => 201,
            'message'   => [
               'success'  => "Berhasil melakukan pengiriman, dan jangan lupa hubungi pembeli mobil anda"
            ]
         ];
         return $this->respond($response);
    }
    else{
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