<?php

namespace app\index\controller;

use prism\Controller;
use prism\Model;

class Index extends Controller {
    public function index() {
        $model = Model::load();
        
        return $this->result;
    }
}