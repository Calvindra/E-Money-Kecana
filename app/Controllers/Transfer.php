<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Transfer extends ResourceController
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
        $token = explode(' ', $header)[1];
      
        try {
            $decoded = JWT::decode($token, new Key ($key, "HS256"));
        
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
        
        $id_user['id']= $decoded->uid; // ini ga bisa di buat if else karena mungkin berbentuk array di if sama get json var yg bukan array

        helper(['form']);
        $rules = [
            'id' => 'required|is_string', // id yg mau ngirim 
            'nohp' => 'required|is_string', //  ngirim ke no hp tujuan 
            'nominaltransfer'=> 'required|is_int'
        ];
        
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        if($this->request->getJsonVar('nominaltransfer') < 0){
            $response = [
                'status' => 400,
                'message'   => [
                   'error'  => "Transfer tidak bisa jika nominal kurang dari 0 "
                ]
             ];
             return $this->respond($response);
        }

        if($this->request->getJsonVar('nominaltransfer') > 1000000){
            $response = [
                'status' => 400,
                'message'   => [
                   'error'  => "Transfer tidak bisa jika nominal lebih dari 1.000.000"
                ]
             ];
             return $this->respond($response);
        }
        
            
        if( $decoded->uid == $this->request->getJsonVar('id')){
           
       
        $model = $db -> table('users');
        $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
        $res = $saldo_pengirim->getResult()[0];
        $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
        $model->where('id', $res->id);
        //$model->set('saldo', false);
        $model->update($data);

        $saldo_tujuan =  $model->getWhere(['nohp'=> $this->request->getVar('nohp')]);
        $restujuan = $saldo_tujuan->getResult()[0];
        $data['saldo'] = $restujuan->saldo+ (int)$this->request->getVar('nominaltransfer');
       // $model->set('saldo', false);
       $model->where('nohp', $restujuan->nohp);
        $model->update($data);
        $response = [
            'status' => 200,
            'message'   => [
               'success'  => "Berhasil transfer"
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
