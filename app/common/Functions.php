<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/26
 * Time: 下午2:29
 * Desc: 公共函数库
 */

namespace app\common;


use prism\Validate;

class Functions {

    /**
     * @param $idType 1：用户ID，2：图标ID，3：数据库连接ID，4仪表盘ID
     * @param $other 某个类型的ID的细分
     *
     * @return int|string
     */
    public static function GenIDS($idType, $other = '') {
        $id  = "";
        $ids = \prism\Model::load('sqlite')->table('ids');
        while (true) {
            if ($idType == "1") {
                $id = "U_" . date("ymd") . rand(100, 999);
            }
            if ($idType == "3") {
                $id = "DB_" . substr(strval($other + 1000), 1, 3) . rand(10000, 99999);
            }
            if (!$ids->where("id = '$id'")->select()) {
                if (!$ids->save(['id' => $id, 'type' => $other])) {
                    \prism\Response::sendError(\app\common\AppCode::ERR_GENERATE_UNIQUE_ID, \app\common\APP_MSG[\app\common\AppCode::ERR_GENERATE_UNIQUE_ID]);
                } else {
                    break;
                }
            }
        }

        return $id;
    }

    /**
     * @param        $value
     * @param        $type
     * @param string $pattern
     * @param string $errno
     *
     * @return int
     * @desc 校验器
     */
    public static function validate($value, $type, $pattern = '', $errno = '') {
        return Validate::validate($value, $type, $pattern, $errno);
    }
}
