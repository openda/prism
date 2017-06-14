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
     * @param $idType 1：用户ID，2：报表ID，3：数据库连接ID，4仪表盘ID
     * @param $other 某个类型的ID的细分
     *
     * @return int|string
     */
    public static function GenIDS($idType, $other = '') {
        $chartMap      = [
            'table'     => 1,
            'histogram' => 2,
            'piechart'  => 3,
            'linechart' => 4,
        ];
        $dataSourceMap = [];
        $dashBoardMap  = [];
        $id            = "";
        $ids           = \prism\Model::load('sqlite')->table('ids');
        while (true) {
            if ($idType == "1") {
                $id = "U_" . date("ymd") . rand(100, 999);
            } else if ($idType == "2") {
                $id = "GR_" . substr(strval($chartMap[$other] + 1000), 1, 3) . rand(10000, 99999);
            } else if ($idType == "3") {
                $id = "DB_" . substr(strval($other + 1000), 1, 3) . rand(10000, 99999);
            } else if ($idType == "4") {
                $id = "DA_" . substr(strval($other + 1000), 1, 3) . rand(10000, 99999);
            } else {
                return false;
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


    /**
     * @param $encryptStr string 待加密的字符串
     * @param $key string 加密关键字
     *
     * @return string
     *
     * @desc 自定义加密算法
     */
    public static function encrypt($encryptStr, $key) {
        if (empty($encryptStr) || empty($key)) {
            return "";
        }
        $dict       = array('1', '2', '3', '4', '5', '6', '7', '8', '9',
                            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                            '-', '=', '*'
        );
        $key        = current(unpack('L', sha1($key, 1)));
        $encryptStr ^= $key;
        $t          = str_split(sprintf('%036b', $encryptStr), 6);
        foreach ($t as &$v) {
            $v = $dict[bindec($v)];
        }

        return join($t);
    }

    /**
     * @param $decryptStr string  待解密的串
     * @param $key string 解密关键字
     *
     * @return string
     *
     * @desc 自定义解密算法
     */
    public static function decrypt($decryptStr, $key) {
        if (empty($decryptStr) || empty($key)) {
            return '';
        }

        $dict = array('1', '2', '3', '4', '5', '6', '7', '8', '9',
                      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                      '-', '=', '*'
        );
        $dict = array_flip($dict);
        foreach (str_split($decryptStr) as $c) {
            $r[] = sprintf('%06b', $dict[$c]);
        }
        $id  = bindec(join($r));
        $key = current(unpack('L', sha1($key, 1)));

        return $id ^ $key;
    }
}
