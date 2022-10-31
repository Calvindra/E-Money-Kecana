<?php
 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Itemmobil;
use App\Models\Usermarketplace;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Buymobil extends ResourceController
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
            'nohppenjual' => 'required|is_string',
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $db = \Config\Database::connect();
        $model = new Itemmobil();
        $model2 = new Usermarketplace();
        $user = $model->where("id", $this->request->getVar('id'))->first();
        if(!$user) return $this->failNotFound('id tidak ditemukan');
 
        $user = $model->where("merk", $this->request->getVar('merk'))->first();
        if(!$user) return $this->failNotFound('merk tidak ditemukan');

        $user = $model2->where("nohp", $this->request->getVar('nohp'))->first();
        if(!$user) return $this->failNotFound(' kamu tidak bisa membeli karena nomor hp kamu belum terdaftar pada marketplace Kecana Rental');

        if( $decoded->nohp == $this->request->getJsonVar('nohp')){
         $hargamobil =  $model->getWhere(['id'=> $this->request->getVar('id')]);
        $harga = $hargamobil->getResult()[0]->harga;
       echo" Harga mobil yang perlu dibayar: $harga";
       
        echo"\n:";
        //echo("Silahkan Hubungi Penjual jika sudah melakukan pembayaran");
    //     exit;

        // jadi nanti di database item mobil itu kasih status kalo ada orang yang mau beli, sama diliatin kalo bisa no hp pembelinya 
        //terus Penjual bisa konfirmasi setelah tau ada yg mau beli itu
        $nopembeli=$this->request->getJsonVar('nohp');
        $data ['status']=     "Akan dibayar oleh $nopembeli";
        // var_dump($data);
        // exit;
        $hp =  $model->getWhere(['id'=> $this->request->getVar('id')]);
        $hpbaru = $hp->getResult()[0];
        //var_dump($hpbaru);

        //$registered = $model->where('id', $hpbaru)->save($data);
        
        $model->set('status', $data);
        $model->where('id', $this->request->getVar('id'));
        $model->update();

        $response = [
            'status' => 200,
            'message'   => [
               'success'  => "Silahkan Confirm ke marketplace jika sudah melakukan pembayaran dan Hubungi Penjual "
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