<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/6/3
 * Time: 上午9:57
 * Desc:
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\prism\BaseController;
use prism\Config;
use prism\File;

class Prism extends BaseController {
    /**
     * @return array
     *
     * @desc 初始化函数
     */
    public function init() {
        $encryptFile = Config::get('encrypt_file');
        $encryptKey  = File::loadFile($encryptFile);
        if ($encryptKey == AppCode::ERR_FILE_INEXISTED) {
            $encryptKey = md5(date("Y-m-d H:i:s"));
            File::write($encryptFile, $encryptKey);
            //修改加密文件权限，非超管不能修改文件内容
            File::recursiveChmod($encryptFile,0644);
        }

        return $this->result;
    }
}