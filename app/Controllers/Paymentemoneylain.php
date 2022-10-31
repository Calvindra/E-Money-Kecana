<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Controllers\BaseController;

class Paymentemoneylain extends BaseController
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

      // login:
      // 1. buski coins-> username dan password 
      // 2. cuan ind-> no telp dan password
      $rules = [
      
        'password' => 'required|is_string',
          // no hp yg mau ngirim 
          'nohp' => 'required|is_string', //  ngirim ke no hp tujuan 
          'nominaltransfer'=> 'required|is_int',
          'emoney'=> 'required|is_string'
      ];
      if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
      
      
     
    if($this->request->getJsonVar('emoney') == "Buski Coins"){
         
         $login_buskidi= 
         [
            "username" => $this->request->getJsonVar('username'),
            "password" => $this->request->getJsonVar('password'),
         ];

         $token=  $this->callAPI('POST', 'https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/publics/login', $login_buskidi, "multipart/form-data", false);
         
         $token = (array) json_decode($token);
         $transfer_buskidi = [
            "nomer_hp"        =>  $this->request->getJsonVar('nohppengirim'), // no rekening penampungnya
            "nomer_hp_tujuan" => $this->request->getJsonVar('nohp'),
            "amount"          => $this->request->getJsonVar('nominaltransfer'),
            "e_money_tujuan"  => "KCN Pay",
         ];
         $token = $token['message']->token;
         //echo $token;
         $response= $this->callAPI('POST', 'https://arielaliski.xyz/e-money-kelompok-2/public/buskidicoin/admin/transfer', $transfer_buskidi, "multipart/form-data", $token);

         $transfer_arr = (array) json_decode($response);
         if($transfer_arr['status'] == 201){
            $response = [
               'status' => 200,
               'message'   => [
                  'success'  => "berhasil transfer dari Buski Coins"
               ]
            ];
            
         }else{
            $response = [
               'status' => 400,
               'message'   => [
                  'error'  => "transfer gagal"
               ]
            ];
         }
         
         return $this->respond($response);
         
     }

     elseif($this->request->getJsonVar('emoney') == "CuanIND"){
         
      $login_CuanIND= 
      [
      "notelp" =>  $this->request->getJsonVar('nohppengirim'),
      "password" => $this->request->getJsonVar('password')
      ];

      $token=  $this->callAPI('POST', 'https://e-money-kelompok5.herokuapp.com/cuanind/user/login',json_encode( $login_CuanIND), "application/json", false);
      //var_dump($login_CuanIND);
      //$token = (array) json_decode($token);
      $transfer_CuanIND = [
         "target" => $this->request->getJsonVar('nohp'),
         "amount"          => (int)$this->request->getJsonVar('nominaltransfer'),
      ];
      
      //$token = $token['message']->token;
      //echo $token;
      $response= $this->callAPI('POST', 'https://e-money-kelompok5.herokuapp.com/cuanind/transfer/KCNPay', json_encode( $transfer_CuanIND), "application/json", $token);
      return $this->respond($response);
     // exit;
//       echo "Berhasil transfer dari emoney CuanIND";
     
//       $model = new UserModel();
//       $saldo_awal =  $model->getWhere(['nohp'=> $this->request->getVar('nohp')]);
//       $res = $saldo_awal->getResult()[0]->saldo;
//       $data ['saldo']=    $res + (int)$this->request->getVar('nominaltransfer');
//       $model->set('saldo', $data);
//       $model->where('nohp', $this->request->getVar('nohp'));
//       $model->update();
//   exit;
   

//       $transfer_arr = (array) json_decode($response);
//       if($transfer_arr['status'] == 201){
//          $response = [
//             'status' => 200,
//             'message'   => [
//                'success'  => "berhasil transfer dari emoney CuanIND"
//             ]
//          ];
         
//       }else{
//          $response = [
//             'status' => 400,
//             'message'   => [
//                'error'  => "transfer gagal"
//             ]
//          ];
//       }
      
      // return $this->respond($response);
      
  }
  elseif($this->request->getJsonVar('emoney') == "MoneyZ"){
         
   $login_MoneyZ= 
   [
      "phone" => $this->request->getJsonVar('nohppengirim'),
      "password" => $this->request->getJsonVar('password')
   ];

   $token=  $this->callAPI('POST', 'https://moneyz-kelompok6.herokuapp.com/api/login',json_encode( $login_MoneyZ), "application/json", false);
   

   $token = (array) json_decode($token);

   $token = $token['token'];
  
   
   
  
   $transfer_MoneyZ = [
      "tujuan" => $this->request->getJsonVar('nohp'),
      "amount"          => (int)$this->request->getJsonVar('nominaltransfer'),
      "emoney"  => "KCN Pay",
   ];
   //
   //echo $token;
   $response= $this->callAPI('POST', 'https://moneyz-kelompok6.herokuapp.com/api/user/transferTo', json_encode( $transfer_MoneyZ), "application/json", $token);


   
   
   return $this->respond($response);
   
}

elseif($this->request->getJsonVar('emoney') == "Gallecoins"){
         
   $login_gallecoins= 
   [
      "username" => $this->request->getJsonVar('username'),
            "password" => $this->request->getJsonVar('password'),
   ];

   $token=  $this->callAPI('POST', 'https://gallecoins.herokuapp.com/api/users',json_encode( $login_gallecoins), "application/json", false);
   
   $token = (array) json_decode($token);


  // $token = $token['token'];
  

  
  $transfer_gallecoins = [
         
   "amount"          => (int)$this->request->getJsonVar('nominaltransfer'),
     "description" => "transfer menggunakan Galle Coins",
     "phone_target"  => $this->request->getJsonVar('nohp'),
   
];

//var_dump($token);
//var_dump($token['message']->token);
$token = $token['token'];


$response= $this->callAPI('POST', 'https://gallecoins.herokuapp.com/api/transfer/kcnpay', json_encode( $transfer_gallecoins), "application/json", $token);



   
   
   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "Peace Pay"){
         
   $login_PeacePay= 
   [
      "number" => $this->request->getJsonVar('nohppengirim'),
      "password" => $this->request->getJsonVar('password'),
   ];

   $token=  $this->callAPI('POST', 'https://e-money-kelompok-12.herokuapp.com/api/login',json_encode( $login_PeacePay), "application/json", false);
   
   $token = (array) json_decode($token);


  // $token = $token['token'];
  

  
  $transfer_PeacePay = [
            
   "tujuan"  => $this->request->getJsonVar('nohp'),
   "amount"          => (int)$this->request->getJsonVar('nominaltransfer'),
   
];
//var_dump($token);
//var_dump($token['message']->token);
$token = $token['token'];


$response= $this->callAPI('POST', 'https://e-money-kelompok-12.herokuapp.com/api/kcnpay', json_encode( $transfer_PeacePay), "application/json", $token);




   
   
   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "Payfresh"){
       
   $login_Payfresh= 
[
   "email" =>  $this->request->getJsonVar('email'),
   "password"  =>$this->request->getJsonVar('password'),
];

$token=  $this->callAPI('POST', 'https://payfresh.herokuapp.com/api/login',json_encode( $login_Payfresh), "application/json", false);

$token = (array) json_decode($token);



  // $token = $token['token'];
  

  
  $transfer_Payfresh = [

   "nohp" => $this->request->getJsonVar('nohp'),
   "nominaltransfer"  => (int)$this->request->getJsonVar('nominaltransfer'),
   
   
   ];
   //var_dump($token);
   //var_dump($token['message']->token);
   $token = $token['token'];
   
   
   $response= $this->callAPI('POST', 'https://payfresh.herokuapp.com/api/user/kcn', json_encode( $transfer_Payfresh), "application/json", $token);

   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "e-COIN"){
       
   $login_eCOIN= 
[
"phone" => $this->request->getJsonVar('nohppengirim'),
"password" => $this->request->getJsonVar('password')
];

$token=  $this->callAPI('POST', 'https://ecoin10.my.id/api/masuk',json_encode($login_eCOIN), "application/json", false);


$token = (array) json_decode($token);



  // $token = $token['token'];
  

  
  $transfer_eCOIN = [
   "amount"   => (int)$this->request->getJsonVar('nominaltransfer'),
   "dest_emoney"   => "KCN Pay",
   "phone2"  => $this->request->getJsonVar('nohp'),
   "description" => "Transfer menggunakan e-COIN berhasil "



];
//var_dump($token);
//var_dump($token['message']->token);
$token = $token['token'];


$response= $this->callAPI('POST', 'https://ecoin10.my.id/api/transfer', json_encode($transfer_eCOIN), "application/json", $token);



   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "PadPay"){
       
   $login_PadPay= 
   [
     "email" =>  $this->request->getJsonVar('email'),
     "password"  =>  $this->request->getJsonVar('password'),
   ];
   
   $token=  $this->callAPI('POST', 'https://mypadpay.xyz/padpay/api/login.php',json_encode( $login_PadPay), "application/json", false);
   
   $token = (array) json_decode($token);
   $token = $token['Data']->jwt;
  // $token = $token['token'];
  
  $transfer_PadPay = [
   "email" =>  $this->request->getJsonVar('email'),
   "password"  => $this->request->getJsonVar('password'),
   "jwt"  => $token,
"tujuan"  => $this->request->getJsonVar('nohp'),
"jumlah" => (int)$this->request->getJsonVar('nominaltransfer'),
];

//var_dump($transfer_PadPay);
//var_dump($transfer_PadPay['jwt']);

//var_dump($transfer_PadPay);

$response= $this->callAPI('PUT', 'https://mypadpay.xyz/padpay/api/coin/kcnpay.php', json_encode($transfer_PadPay), "application/json", false);



   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "Talangin"){
       
   $login_Talangin= 
[
  "email" =>   $this->request->getJsonVar('email'),
  "password"  =>  $this->request->getJsonVar('password')
];

$token=  $this->callAPI('POST', 'https://e-money-kelomok-11.000webhostapp.com/api/login.php',json_encode( $login_Talangin), "application/json", false);

$token = (array) json_decode($token);
$token = $token['jwt'];


$transfer_Talangin = [
"jwt"  => $token,
"pengirim" =>  $this->request->getJsonVar('nohppengirim'),
"penerima"  => $this->request->getJsonVar('nohp'),
"emoney"  => "KCN Pay",
"jumlah" => (int)$this->request->getJsonVar('nominaltransfer'),
];

//var_dump($transfer_PadPay);
//var_dump($transfer_PadPay['jwt']);

//var_dump($transfer_PadPay);

$response= $this->callAPI('POST', 'https://e-money-kelomok-11.000webhostapp.com/api/transferin.php', json_encode($transfer_Talangin), "application/json", false);

   return $this->respond($response);
   
}
elseif($this->request->getJsonVar('emoney') == "Payphone"){
       
   $login_Payphone= 
      [
         "telepon" =>  $this->request->getJsonVar('nohppengirim'),
         "password" =>  $this->request->getJsonVar('password')
      ];

      $token=  $this->callAPI('POST', 'http://fp-payphone.herokuapp.com/public/api/login', $login_Payphone, "multipart/form-data", false);
      
      $token = (array) json_decode($token);
      $transfer_Payphone = [
         "telepon" => $this->request->getJsonVar('nohp'),
         "jumlah"          => (int)$this->request->getJsonVar('nominaltransfer'),
         "emoney"  => "KCN"
      ];
      $token = $token['token'];
      //var_dump($token);
      
      //$token = $token['message']->token;
      //echo $token;
      $response= $this->callAPI('POST', 'http://fp-payphone.herokuapp.com/public/api/transfer', $transfer_Payphone, "multipart/form-data", $token);

   return $this->respond($response);
   
}
         
  else{
   $response = [
      'status' => 400,
      'message'   => [
         'error'  => "gagal memilih payment karena Emoney tersebut tidak ditemukan"
      ]
   ];
   return $this->respond($response);
}
  


    

     
}
}
