<?php namespace App\Controllers;

use App\Models\OrderModel;
use \Firebase\JWT\JWT;
use App\Models\UserModel;
use App\Controllers\Auth;

class User extends BaseController
{
    private $user_model;
    private $order_model;

    public function __construct()
    {
        $read_db = db_connect('read');
        $this->user_model = model('UserModel', true, $read_db);
        $this->order_model = model('OrderModel', true, $read_db);
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            $auth = new Auth();
            $token = $this->request->getHeader('Authorization');

            if($token) {
                array_push($params, $auth->verify($token));

                $result = call_user_func_array(array($this, $method), $params);
                $this->response->setContentType('application/json');
                return $this->response->setJSON($result);
            } else {
                return $this->response->setStatusCode(401);
            }
        } else {
            return $this->response->setStatusCode(404);
        }
    }

    public function user($payload)
    {
        $user_id = $payload->user->id;
        $user = $this->user_model->find($user_id);

        return [
            'name' => $user['name'],
            'nickname' => $user['nickname'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'gender' => $user['gender']
        ];
    }

    public function users()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $limit = $this->request->getPost('limit');
        $offset = $this->request->getPost('offset');

        if ($name and $email) {
            $user = $this->user_model
                ->join('orders', 'users.id = orders.user_id')
                ->where('users.name', $name)
                ->where('users.email', $email)
                ->limit(1)
                ->orderBy('created_at', 'DESC')
                ->first();

            return [
                'name' => $user['name'],
                'nickname' => $user['nickname'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'gender' => $user['gender'],
                'last_order' => $user['order_no']
            ];
        } else {
            $users = $this->user_model
                ->limit($limit)
                ->offset($offset);

            $func = function ($user) {
                $last_order = $this->order_model
                    ->where('user_id', $user['id'])
                    ->limit(1)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                return [
                    'name' => $user['name'],
                    'nickname' => $user['nickname'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'gender' => $user['gender'],
                    'last_order' => $last_order
                ];
            };
            return array_map($func, (array)$users);
        }
    }
}