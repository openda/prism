<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午10:34
 * Desc:
 */

namespace prism;

use const prism\common\PRISM_MSG;
use prism\common\PrismCode;

class File {
    public static function loadConfig($file, $parse = CONF_PARSE) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'php':
                return include $file;
            case 'ini':
                return parse_ini_file($file);
            case 'yaml':
                return yaml_parse_file($file);
            case 'xml':
                return (array)simplexml_load_file($file);
            case 'json':
                return json_decode(file_get_contents($file), true);
            default:
                if (function_exists($parse)) {
                    return $parse($file);
                } else {
                    //                    E('_NOT_SUPPORT_' . ':' . $ext);
                    // 没有相应的解析函数解析自定义的配置文件
                    Response::send(PrismCode::ERR_CONF_PARSE, PRISM_MSG[PrismCode::ERR_CONF_PARSE]);
                    exit;
                }
        }
    }

    /**
     * @param     $path
     * @param int $filePerm
     * @param int $dirPerm
     *
     * @return bool
     *
     * 批量修改文件夹权限
     */
    public static function recursiveChmod($path, $filePerm = 0775, $dirPerm = 0775) {
        //         Check if the path exists
        if (!file_exists($path)) {
            return (false);
        }
        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            chmod($path, $filePerm);
            // If this is a directory...
        } elseif (is_dir($path)) {
            // Then get an array of the contents
            $foldersAndFiles = scandir($path);
            // Remove "." and ".." from the list
            $entries = array_slice($foldersAndFiles, 2);
            // Parse every result...
            foreach ($entries as $entry) {
                // And call this function again recursively, with the same permissions
                self::recursiveChmod($path . "/" . $entry, $filePerm, $dirPerm);
            }
            // When we are done with the contents of the directory, we chmod the directory itself
            chmod($path, $dirPerm);
        }

        // Everything seemed to work out well, return TRUE
        return (true);
    }
}