<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Additem extends ResourceController
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
            'merk' => 'required|is_string',
            'nohp' => 'required|is_string',
            'lokasi' => 'required|is_string',
            'kondisi' => 'required|is_string',
            'deskripsi' => 'required|is_string',
            'harga' => 'required|is_int'
        ];
        
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model2 = new Usermarketplace();
        $user = $model2->where("nohp", $this->request->getVar('nohp'))->first();
        if(!$user) return $this->failNotFound('nohp tidak ditemukan');
        //exit;
        if( $decoded->nohp == $this->request->getJsonVar('nohp')){
        $data = [
            'merk'     => $this->request->getVar('merk'),
            'nohp'     => $this->request->getVar('nohp'),
            'lokasi'     => $this->request->getVar('lokasi'),
            'kondisi'     => $this->request->getVar('kondisi'),
            'deskripsi'     => $this->request->getVar('deskripsi'),
            'harga'     => $this->request->getVar('harga')
        ];
        
    
        $db = \Config\Database::connect();
        $model = $db->table('itemmobil');
        
        $registered = $model->insert($data);
        $this->respondCreated($registered);
     
        
        //$this->respondCreated($registered);
 
        $response = [
            'status' => 201,
            'message'   => [
               'success'  => "Berhasil menambahkan mobil yang dijual"
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