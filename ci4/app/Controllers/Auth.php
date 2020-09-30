<?php namespace App\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use App\Models\UserModel;

class Auth extends BaseController
{
    private $user_model;

    public function __construct()
    {
        $this->user_model = new UserModel();
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            $result = call_user_func_array(array($this, $method), $params);
            $this->response->setContentType('application/json');
            return $this->response->setJSON($result);

        } else {
            return $this->response->setStatusCode(404);
        }
    }

    public function privateKey()
    {
        return "hello";
    }

    public function verify($token)
    {
        try {
            $secret_key = $this->privateKey();
            $decoded = JWT::decode($token, $secret_key, array('HS256'));
            return ['payload' => $decoded];
        } catch (\Exception $e) {
            return $this->response->setStatusCode(401);
        }
    }

    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[20]',
            'nickname' => 'required|min_length[3]|max_length[20]',
            'password' => 'required|min_length[8]|max_length[255]',
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'phone' => 'required|min_length[6]|max_length[50]',
            'gender' => 'permit_empty|min_length[1]|max_length[6]',
        ];

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(400);
            return "validation error";
        } else {
            $newData = [
                'name' => $this->request->getPost('name'),
                'nickname' => $this->request->getPost('nickname'),
                'password' => $this->request->getPost('password'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'gender' => $this->request->getPost('gender'),
            ];
            return $this->user_model->save($newData);
        }
    }

    public function login()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'password' => 'required|min_length[8]|max_length[255]',
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Email or Password don\'t match'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return $this->response->setStatusCode(400);
        } else {
            $user = $this->user_model
                ->where('email', $this->request->getPost('email'))
                ->first();

            $secret_key = $this->privateKey();
            $token = array(
                'id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'isLoggedIn' => true,
                'iat' => time()
            );

            $token = JWT::encode($token, $secret_key);
            return ['token' => $token];
        }
    }
}