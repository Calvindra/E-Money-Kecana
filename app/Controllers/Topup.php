<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 
class Topup extends ResourceController
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
        
        helper(['form']);
        $rules = [
            'kodeakses' => 'required|is_string',
            'nohp'=> 'required|is_string',
            'saldo' => 'required|is_int'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        if( $decoded->uid == 67){
        if ($this->request->getJsonVar('kodeakses') == "5rotidan2ikan"){
        $data = [
            'saldo'     => $this->request->getVar('saldo')
        ];
        $model = $db -> table('users');
        $saldo_awal =  $model->getWhere(['nohp'=> $this->request->getVar('nohp')]);
        $res = $saldo_awal->getResult()[0];
        $data['saldo'] = $res->saldo+ (int)$data['saldo'];
        $model->set('saldo', false);
        $model->where('nohp',  $this->request->getVar('nohp'));
        $model->update($data);
  
       // $sql = "INSERT INTO historytrans( created_at ) 
        //VALUES( '2018-12-05 12:39:16' );";
        
       // $hist =  $model->getWhere(['historytrans'=> $this->request->getVar('historytrans')]);
        //$model->set('historytrans', false);
        //$t=time();
        //$model = $hist->save("Y-m-d H:i:s",$t); 

        $response = [
            'status' => 200,
            'message'   => [
               'success'  => "Terima kasih, anda berhasil Top Up:)"
            ]
         ];
         return $this->respond($response);
 
        }
        else{
            $response = [
               'status' => 400,
               'message'   => [
                  'error'  => "Mohon maaf, kode akses anda salah :("
               ]
            ];
            return $this->respond($response);
         }
        }
        else{
            $response = [
               'status' => 400,
               'message'   => [
                  'error'  => "Mohon maaf, akses top up hanya dimiliki oleh admin :("
               ]
            ];
            return $this->respond($response);
         }
    }
 
}
