<?php 
namespace prism;

use const prism\common\ERR_MSG;
use prism\common\ErrCode;

class IndexController extends Controller{
    public function index() {
        Response::send(ErrCode::SUCCESS, ERR_MSG[ErrCode::SUCCESS]);
    }
}