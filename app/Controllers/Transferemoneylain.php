<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Controllers\BaseController;

class Transferemoneylain extends BaseController
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
          'id' => 'required|is_string', // id yg mau ngirim 
          'nohp' => 'required|is_string', //  ngirim ke no hp tujuan 
          'nominaltransfer'=> 'required|is_int',
          'emoneytujuan'=> 'required|is_string'
      ];
      if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
      
      /*if($data['emoneytujuan'] == "buskidicoin"){
         $model = $db -> table('users');
         $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
         $res = $saldo_pengirim->getResult()[0];
         $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
         $model->where('id', $res->id);
         //$model->set('saldo', false);
         $model->update($data);
      $method='POST';
      $url= "https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/publics/login";
      $data= 
         [
            "username" => "penampungcalvin",
            "password" => "penampungcalvin"
         ];
         //var_dump($this->callAPI($method, $url, $data, false));
         //$this->callAPI($method, $url, $data, false);
         //$token= $this->callAPI($method, $url, $data, false, json_encode($data));


      }*/
      /*$model = $db -> table('users');
         $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
         $res = $saldo_pengirim->getResult()[0];
         $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
         $model->where('id', $res->id);
         //$model->set('saldo', false);
         $model->update($data);*/
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
    if($this->request->getJsonVar('emoneytujuan') == "Buski Coins"){
      $model = $db -> table('users');
         $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
         $res = $saldo_pengirim->getResult()[0];
         $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
         $model->where('id', $res->id);
         //$model->set('saldo', false);
         $model->update($data);
         
         $login_buskidi= 
         [
            "username" => "penampungcalvin",
            "password" => "penampungcalvin"
         ];

         $token=  $this->callAPI('POST', 'https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/publics/login', $login_buskidi, "multipart/form-data", false);
         
         $token = (array) json_decode($token);
         $transfer_buskidi = [
            "nomer_hp"        => "0811811821", // no rekening penampungnya
            "nomer_hp_tujuan" => $this->request->getJsonVar('nohp'),
            "amount"          => $this->request->getJsonVar('nominaltransfer'),
            "e_money_tujuan"  => $this->request->getJsonVar('emoneytujuan'),
         ];
         $token = $token['message']->token;
         //echo $token;
         $response= $this->callAPI('POST', 'https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/admin/transfer', $transfer_buskidi, "multipart/form-data", $token);

         $transfer_arr = (array) json_decode($response);
         if($transfer_arr['status'] == 201){
            $response = [
               'status' => 200,
               'message'   => [
                  'error'  => "berhasil transfer ke Buski Coins"
               ]
            ];
            
         }else{
            $response = [
               'status' => 400,
               'message'   => [
                  'error'  => "transfer ke Buski Coins gagal"
               ]
            ];
         }
         
         return $this->respond($response);
         
     }
         //var_dump($token['message']->token);
         elseif($this->request->getJsonVar('emoneytujuan') == "Peace Pay"){
            
            $model = $db -> table('users');
            $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
            $res = $saldo_pengirim->getResult()[0];
            $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
            $model->where('id', $res->id);
            //$model->set('saldo', false);
            $model->update($data);
            $login_PeacePay= 
            [
               "number" => "0811811821",
               "password" => "penampungkecana"
            ];
   
            $token=  $this->callAPI('POST', 'https://e-money-kelompok-12.herokuapp.com/api/login',json_encode( $login_PeacePay), "application/json", false);
            
            $token = (array) json_decode($token);
            $transfer_PeacePay = [
            
               "tujuan"  => $this->request->getJsonVar('nohp'),
               "amount"          => $this->request->getJsonVar('nominaltransfer'),
               
            ];
            //var_dump($token);
            //var_dump($token['message']->token);
            $token = $token['token'];
           
            $response= $this->callAPI('POST', 'https://e-money-kelompok-12.herokuapp.com/api/transfer', json_encode( $transfer_PeacePay), "application/json", $token);
   
           
            
            return $this->respond($response);
            
        }
        elseif($this->request->getJsonVar('emoneytujuan') == "Gallecoins"){
            
         $model = $db -> table('users');
         $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
         $res = $saldo_pengirim->getResult()[0];
         $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
         $model->where('id', $res->id);
         //$model->set('saldo', false);
         $model->update($data);

         $login_gallecoins= 
         [
            "username" => "penampungkecana",
            "password" => "penampungkecana"
         ];

         $token=  $this->callAPI('POST', 'https://gallecoins.herokuapp.com/api/users',json_encode( $login_gallecoins), "application/json", false);
         
         $token = (array) json_decode($token);
         $transfer_gallecoins = [
         
            "amount"          => $this->request->getJsonVar('nominaltransfer'),
              "phone"  => $this->request->getJsonVar('nohp'),
              "description" => "transfer emoney lain"
            
         ];
         //var_dump($token);
         //var_dump($token['message']->token);
         $token = $token['token'];
        
         $response= $this->callAPI('POST', 'https://gallecoins.herokuapp.com/api/transfer', json_encode( $transfer_gallecoins), "application/json", $token);

        
         
         return $this->respond($response);
         
     }
     elseif($this->request->getJsonVar('emoneytujuan') == "MoneyZ"){
            $model = $db -> table('users');
         $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
         $res = $saldo_pengirim->getResult()[0];
         $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
         $model->where('id', $res->id);
         //$model->set('saldo', false);
         $model->update($data);
         
      $login_MoneyZ= 
      [
         "phone" => "0811811821",
         "password" => "penampungkecana"
      ];

      $token=  $this->callAPI('POST', 'https://moneyz-kelompok6.herokuapp.com/api/login',json_encode( $login_MoneyZ), "application/json", false);
      
      $token = (array) json_decode($token);
      $transfer_MoneyZ = [
         "nomortujuan"  => $this->request->getJsonVar('nohp'),
         "nominal"          => $this->request->getJsonVar('nominaltransfer'),
         
         
      ];
      //var_dump($token);
      //var_dump($token['message']->token);
      $token = $token['token'];
     
      $response= $this->callAPI('POST', 'https://moneyz-kelompok6.herokuapp.com/api/user/transfer', json_encode( $transfer_MoneyZ), "application/json", $token);

     
      
      return $this->respond($response);
      
  }
  elseif($this->request->getJsonVar('emoneytujuan') == "e-COIN"){
   $model = $db -> table('users');
$saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
$res = $saldo_pengirim->getResult()[0];
$data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
$model->where('id', $res->id);
//$model->set('saldo', false);
$model->update($data);

$login_eCOIN= 
[
"phone" => "0811811821",
"password" => "penampungkecana"
];

$token=  $this->callAPI('POST', 'https://ecoin10.my.id/api/masuk',json_encode( $login_eCOIN), "application/json", false);

$token = (array) json_decode($token);
$transfer_eCOIN = [
   "phone" => "0811811821",
   "tfmethod" => 1,
   "amount"   => $this->request->getJsonVar('nominaltransfer'),
   "phone2"  => $this->request->getJsonVar('nohp'),
   "description" => "Transfer ke e-COIN "



];
//var_dump($token);
//var_dump($token['message']->token);
$token = $token['token'];

$response= $this->callAPI('POST', 'https://ecoin10.my.id/api/transfer', json_encode( $transfer_eCOIN), "application/json", $token);



return $this->respond($response);

}
elseif($this->request->getJsonVar('emoneytujuan') == "CuanIND"){
   $model = $db -> table('users');
$saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
$res = $saldo_pengirim->getResult()[0];
$data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
$model->where('id', $res->id);
//$model->set('saldo', false);
$model->update($data);

$login_CuanIND= 
[
"notelp" => "0811811821",
"password" => "penampungkecana"
];

$token=  $this->callAPI('POST', 'https://e-money-kelompok5.herokuapp.com/cuanind/user/login',json_encode( $login_CuanIND), "application/json", false);

//$token = (array) json_decode($token);
$transfer_CuanIND = [
   "target"  => $this->request->getJsonVar('nohp'),
   "amount"          => $this->request->getJsonVar('nominaltransfer'),
   


];
//var_dump($token);
//var_dump($token['message']->token);
//$token = $token['token'];

$response= $this->callAPI('POST', 'https://e-money-kelompok5.herokuapp.com/cuanind/transfer', json_encode( $transfer_CuanIND), "application/json", $token);



return $this->respond($response);

}

elseif($this->request->getJsonVar('emoneytujuan') == "Payfresh"){
    $model = $db -> table('users');
$saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
$res = $saldo_pengirim->getResult()[0];
$data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
$model->where('id', $res->id);
//$model->set('saldo', false);
$model->update($data);

$login_Payfresh= 
[
   "email" =>  " kecana@gmail.com",
   "password"  => "penampungkecana"
];

$token=  $this->callAPI('POST', 'https://payfresh.herokuapp.com/api/login',json_encode( $login_Payfresh), "application/json", false);

$token = (array) json_decode($token);


$transfer_Payfresh = [

"amount" => $this->request->getJsonVar('nominaltransfer'),
"phone"  => $this->request->getJsonVar('nohp'),


];
//var_dump($token);
//var_dump($token['message']->token);
$token = $token['token'];

$response= $this->callAPI('POST', 'https://payfresh.herokuapp.com/api/user/transfer/34', json_encode( $transfer_Payfresh), "application/json", $token);



return $this->respond($response);

}

elseif($this->request->getJsonVar('emoneytujuan') == "PadPay"){
   $model = $db -> table('users');
$saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
$res = $saldo_pengirim->getResult()[0];
$data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
$model->where('id', $res->id);
//$model->set('saldo', false);
$model->update($data);

$login_PadPay= 
[
  "email" =>  "kecana@gmail.com",
  "password"  => "penampungkecana"
];

$token=  $this->callAPI('POST', 'https://mypadpay.xyz/padpay/api/login.php',json_encode( $login_PadPay), "application/json", false);

$token = (array) json_decode($token);
$token = $token['Data']->jwt;

$transfer_PadPay = [
   "email" =>  "kecana@gmail.com",
   "password"  => "penampungkecana",
   "jwt"  => $token,
"tujuan"  => $this->request->getJsonVar('nohp'),
"jumlah" => $this->request->getJsonVar('nominaltransfer'),
];

//var_dump($transfer_PadPay);
//var_dump($transfer_PadPay['jwt']);

//var_dump($transfer_PadPay);

$response= $this->callAPI('PUT', 'https://mypadpay.xyz/padpay/api/transaksi.php/60', json_encode($transfer_PadPay), "application/json", false);



return $this->respond($response);

}

elseif($this->request->getJsonVar('emoneytujuan') == "Payphone"){
   $model = $db -> table('users');
      $saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
      $res = $saldo_pengirim->getResult()[0];
      $data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
      $model->where('id', $res->id);
      //$model->set('saldo', false);
      $model->update($data);
      
      $login_Payphone= 
      [
         "telepon" => "0811811821",
         "password" => "penampungkecana"
      ];

      $token=  $this->callAPI('POST', 'http://fp-payphone.herokuapp.com/public/api/login', $login_Payphone, "multipart/form-data", false);
      
      $token = (array) json_decode($token);
      $transfer_Payphone = [
         "telepon" => $this->request->getJsonVar('nohp'),
         "jumlah"          => $this->request->getJsonVar('nominaltransfer'),
         "emoney"  => "payphone"
      ];
      $token = $token['token'];
      //var_dump($token);
      
      //$token = $token['message']->token;
      //echo $token;
      $response= $this->callAPI('POST', 'http://fp-payphone.herokuapp.com/public/api/transfer', $transfer_Payphone, "multipart/form-data", $token);

      
      return $this->respond($response);
      
  }

  elseif($this->request->getJsonVar('emoneytujuan') == "Talangin"){
   $model = $db -> table('users');
$saldo_pengirim =  $model->getWhere(['id'=> $this->request->getVar('id')]);
$res = $saldo_pengirim->getResult()[0];
$data['saldo'] = $res->saldo - (int)$this->request->getVar('nominaltransfer');
$model->where('id', $res->id);
//$model->set('saldo', false);
$model->update($data);

$login_Talangin= 
[
  "email" =>  "kecana@gmail.com",
  "password"  => "penampungkecana"
];

$token=  $this->callAPI('POST', 'https://e-money-kelomok-11.000webhostapp.com/api/login.php',json_encode( $login_Talangin), "application/json", false);

$token = (array) json_decode($token);
$token = $token['jwt'];


$transfer_Talangin = [
"jwt"  => $token,
"pengirim" =>  "0811811821",
"penerima"  => $this->request->getJsonVar('nohp'),
"jumlah" => $this->request->getJsonVar('nominaltransfer'),
];

//var_dump($transfer_PadPay);
//var_dump($transfer_PadPay['jwt']);

//var_dump($transfer_PadPay);

$response= $this->callAPI('POST', 'https://e-money-kelomok-11.000webhostapp.com/api/transfer.php', json_encode($transfer_Talangin), "application/json", false);



return $this->respond($response);

}
  else{
   $response = [
      'status' => 400,
      'message'   => [
         'error'  => "Mohon maaf, Emoney yang ingin ditransfer tidak terdaftar"
      ]
   ];
   return $this->respond($response);
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
