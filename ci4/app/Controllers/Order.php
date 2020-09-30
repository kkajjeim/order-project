<?php namespace App\Controllers;

use App\Controllers\Auth;
use App\Models\OrderModel;

class Order extends BaseController
{
    private $order_model;

    public function __construct()
    {
        $read_db = db_connect('read');
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

    public function orders($payload)
    {
        $user_id = $payload->user->id;

        $orders = $this->order_model->where('user_id', $user_id)->findAll();
        return $this->response
            ->setStatusCode(200)
            ->setJSON($orders);
    }
}