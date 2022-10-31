<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Usermarketplace;
 
class Registermp extends ResourceController
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
            'alamat' => 'required|is_string',
            'password' => 'required|min_length[6]',
            'confpassword' => 'matches[password]'
        ];
        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $data = [
            'email'     => $this->request->getVar('email'),
            'nohp'     => $this->request->getVar('nohp'),
            'alamat'     => $this->request->getVar('alamat'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT) 
        ];
        $db = \Config\Database::connect();
        $model = $db->table('usermarketplace');
        
        $registered = $model->insert($data);
        $this->respondCreated($registered);
 
        $response = [
            'status' => 201,
            'message'   => [
               'success'  => "Berhasil membuat akun untuk marketplace Kecana"
            ]
         ];
         return $this->respond($response);
    }
 
}