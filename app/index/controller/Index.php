<?php

namespace app\index\controller;

use prism\Controller;
use prism\Model;

class Index extends Controller {
    public function get() {
        $model = Model::load('sqlite');
//        $this->result['data'] = $model->from('user')->where('user_id = ?', array('ssss'))->select();

//        $data['user_id'] = 'ssss';
        $data['user_name'] = 'piaobeizu1';
//        $data['user_phone']  = '18611470725';
//        $data['create_time'] = '2017-04-20 21:35:48';
//        $data['update_time'] = '2017-04-20 21:35:48';
//
        $this->result['data'] = $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdadda'))->update($data);


//        $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdaddad'))->delete();
//        $this->result['data']    = $model->from('user')->where('user_id = :user_id', array(':user_id' => 'sdadda'))->select();
//        $this->result['data'] = $model->from('user')->select();
//        $this->result['data'] = $model->structure('user');
        $this->result['data'] = "Welcome to Prism World!";
        return $this->result;
    }
}