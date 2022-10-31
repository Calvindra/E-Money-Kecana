<?php
 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Confirmorder extends ResourceController
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
            'nohp' => 'required|is_string', 
            'id' => 'required|is_int', 
            'merk' => 'required|is_string', 
            'role' => 'required|is_string', //disini diisi penjual apa pembeli
            'konfirmasi' => 'required|is_string'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $db = \Config\Database::connect();
        $model = new Itemmobil();
        $model2 = new Usermarketplace();
        $user = $model->where("id", $this->request->getVar('id'))->first();
        if(!$user) return $this->failNotFound('id tidak ditemukan');
 

        $user = $model2->where("nohp", $this->request->getVar('nohp'))->first();
        if(!$user) return $this->failNotFound('mohon maaf, nomor hp ini tidak terdaftar');

        if( $decoded->nohp == $this->request->getJsonVar('nohp')){
        if($this->request->getJsonVar('role') == "Pembeli"){
            if($this->request->getJsonVar('konfirmasi') == "Lunas"){
                $nopembeli=$this->request->getJsonVar('nohp');
                $data ['status']=     "Sudah lunas dibayar oleh $nopembeli";
                $model->set('status', $data);
                $model->where('id', $this->request->getVar('id'));
                $model->update();

                $response = [
                    'status' => 200,
                    'message'   => [
                       'success'  => "Berhasil, tunggu nanti penjual akan mengkonfirmasi untuk mengirim ke lokasimu"
                    ]
                 ];
                 return $this->respond($response);
            }
         
        }

        elseif($this->request->getJsonVar('role') == "Penjual"){
            $hp =  $model->getWhere(['id'=> $this->request->getVar('id')]);
             $hpbaru = $hp->getResult()[0]->nohp;
            if($this->request->getJsonVar('nohp') ==  $hpbaru  ){


                        if($this->request->getJsonVar('konfirmasi') == "Lunas"){
                            
                            $model->where('id', $this->request->getVar('id'));
                            $model->delete();


                            $response = [
                                'status' => 200,
                                'message'   => [
                                'success'  => "Selamat barangmu sudah terjual"
                                ]
                            ];
                            return $this->respond($response);
                        }
        }
        else{
            $response = [
                'status' => 400,
                'Failed'   => [
                'success'  => "Tidak bisa mengkonfirmasi karena nomor Hp anda tidak sesuai dengan nomor Hp mobil yang dijual "
                ]
            ];
            return $this->respond($response);
            
        }
        }
        

        
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