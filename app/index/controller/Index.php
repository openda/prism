<?php

namespace app\index\controller;

use prism\Controller;
use prism\Model;

class Index extends Controller {
    public function index() {
        $model                 = Model::load('sqlite');
        $this->result['data'] = $model->from('user')->select();

        return $this->result;
    }
}