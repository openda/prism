<?php

namespace app\index\controller;

use prism\Controller;
use prism\Model;

class Index extends Controller {
    public function index() {
        $model = Model::load('sqlite');
//        $this->result['data'] = $model->from('user')->where('user_id = ?', array('ssss'))->select();

//        $data['user_id']     = 'sdaddad';
        $data['user_name'] = [':user_name', 'piaobeizu'];
//        $data['user_phone']  = '18611470725';
//        $data['create_time'] = '2017-04-20 21:35:48';
//        $data['update_time'] = '2017-04-20 21:35:48';
//
        $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdaddad'))->update($data);
        $this->result['data'] = $model->from('user')->where('user_id = :user_id', array(':user_id' => 'sdaddad'))->select();

//        $this->result['data'] = $model->from('user')->select();

        return $this->result;
    }
}