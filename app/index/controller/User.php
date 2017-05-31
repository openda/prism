<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/19
 * Time: 下午11:30
 * Desc: user资源
 */

namespace app\index\controller;

use app\common\AppCode;
use app\common\Functions;
use app\index\BaseController;
use prism\Logger;
use prism\Model;
use prism\Session;

class User extends BaseController {
    /**
     * @param $userName
     * @param $password
     * @param $email
     * @param $phone
     *
     * @return array|int
     * @desc 添加用户
     */
    public function addUser($user_name, $password, $email, $phone) {
        $User = Model::load('sqlite')->table('user');
        //查询该用户是否已经存在
        if (!empty($User->where('user_name = :user_name', [':user_name' => trim($user_name)])->select())) {
            return AppCode::APP_USER_EXISTED;
        }
        $now = date("Y-m-d H:i:s");

        //添加新用户
        $data['user_id']     = Functions::GenIDS(1);
        $data['user_name']   = $user_name;
        $data['user_email']  = $email;
        $data['user_phone']  = $phone;
        $data['user_pwd']    = md5($password);
        $data['create_time'] = $now;
        $data['update_time'] = $now;
        $data['status']      = 1;

        $User->save($data);

        return $this->result;
    }

    /**
     * @param $user_name
     * @param $password
     *
     * @return array|int
     *
     * @desc 用户登录接口
     */
    public function login($user_name, $password) {
        $user     = Model::load('sqlite')->table('user');
        $userInfo = $user->where('user_name = ?', array(trim($user_name)))->select("user_name , user_pwd");
        Logger::debug("用户信息：", $userInfo);
        //查询该用户是否已经存在
        if (empty($userInfo)) {
            return AppCode::APP_USER_INISTED;
        }
        if (md5($password) != $userInfo["user_pwd"]) {
            return AppCode::ERR_USER_PASSWORD;
        }
        $userInfo = $user->where('user_name = ?', array(trim($user_name)))->select("user_name , user_id");
        //记录登录用户信息到session
        Session::set("user_name", $userInfo);

        return $this->result;
    }

    /**
     * @param $user_name String 用户名
     * @param $password String 密码
     * @param $email String 邮箱
     * @param $phone String 手机号
     *
     * @desc 更新用户信息
     */
    public function updateUser($user_name, $password, $email, $phone) {
        $user     = Model::load('sqlite')->table('user');
        $userInfo = $user->where('user_name = :user_name', [':user_name' => trim($user_name)])->select();
        //查询该用户是否已经存在
        if (empty($userInfo)) {
            return AppCode::APP_USER_INISTED;
        }

        $now = date("Y-m-d H:i:s");
        if (!empty($email)) {
            $data['user_email'] = [":user_email", $email];
        }
        if (!empty($phone)) {
            $data['user_phone'] = [":user_phone", $phone];
        }
        if (!empty($password)) {
            $data['user_pwd'] = [":user_pwd", md5($password)];
        }
        $data['update_time'] = [":update_time", $now];

        if (!$user->where('user_name = :user_name', array(":user_name" => $user_name))->update($data)) {
            return AppCode::ERR_UPDATE_USER;
        }

        return $this->result;
    }
}