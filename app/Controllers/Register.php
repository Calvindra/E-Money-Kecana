<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
 
class Register extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'nohp' => 'required|is_string',
            'password' => 'required|min_length[6]',
            'confpassword' => 'matches[password]'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $data = [
            'email'     => $this->request->getVar('email'),
            'nohp'     => $this->request->getVar('nohp'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT) 
        ];
        //var_dump($data);
      
        
        $db = \Config\Database::connect();
        $model = $db->table('users');
        
        $registered = $model->insert($data);
        $this->respondCreated($registered);
        //var_dump($data);
       // $this->respondCreated($registered);
 
        $response = [
            'status' => 201,
            'message'   => [
               'success'  => "Berhasil membuat akun untuk e-money"
            ]
         ];
         return $this->respond($response);
    }
 
}