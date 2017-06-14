<?php

namespace app\prism\controller;

use prism\Controller;
use prism\Model;

class Index extends Controller {
    public function show() {
        $model = Model::load('sqlite');
//        $this->result['data'] = $model->from('user')->where('user_id = ?', array('ssss'))->select();

//        $data['user_id'] = 'ssss';
        $data['user_name'] = 'piaobeizu1';
//        $data['user_phone']  = '18611470725';
//        $data['create_time'] = '2017-04-20 21:35:48';
//        $data['update_time'] = '2017-04-20 21:35:48';
//
//        $this->result['data'] = $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdadda'))->update($data);


//        $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdaddad'))->delete();
//        $this->result['data']    = $model->from('user')->where('user_id = :user_id', array(':user_id' => 'sdadda'))->select();
//        $this->result['data'] = $model->from('user')->select();
//        $this->result['data'] = $model->structure('user');
        $this->result['data'] = "Welcome to Prism World!";

        return $this->result;
    }

    public function getMysql() {
        $model = Model::load('mysql', ["host" => "127.0.0.1", "port" => 3306, "user" => "root", "password" => "", "dbname" => "prism"]);

        $data['name']     = [":name", 'piaobeizu1'];
        $data['password'] = [":password", '123456'];
//        $this->result['data'] = $model->table('user')->save($data);
        $this->result['data'] = $model->table('user')->where('id = :id', array(':id' => '1'))->update($data);


//        $model->table('user')->where('user_id = :user_id', array(':user_id' => 'sdaddad'))->delete();
//        $this->result['data']    = $model->from('user')->where('user_id = :user_id', array(':user_id' => 'sdadda'))->select();
//        $this->result['data'] = $model->from('user')->select();
//        $this->result['data'] = $model->structure('user');
        $this->result['data'] = "Welcome to Prism World!";

        return $this->result;
    }
}