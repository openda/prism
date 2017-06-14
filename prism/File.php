<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 17/3/29
 * Time: 下午10:34
 * Desc:
 */

namespace prism;

use app\common\AppCode;
use const prism\common\PRISM_MSG;
use prism\common\PrismCode;
use prism\core\exception\ErrorException;

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
                    Response::sendError(PrismCode::ERR_CONF_PARSE, PRISM_MSG[PrismCode::ERR_CONF_PARSE]);
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
            system("chmod -R " . $dirPerm . " $path");
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
            system("chmod -R " . $dirPerm . " $path");
//            chmod($path, $dirPerm);
        }

        // Everything seemed to work out well, return TRUE
        return (true);
    }

    /**
     * @param $file
     *
     * @return bool|int|string
     *
     * @desc 加载文件并返回文件内容
     */
    public static function loadFile($file) {
        if (is_file($file)) {
            return file_get_contents($file);
        }

        return AppCode::ERR_FILE_INEXISTED;
    }


    /**
     * @param        $file
     * @param        $content
     * @param string $type
     *
     * @desc 写文件
     */
    public static function write($file, $content, $type = 'w') {
        try {
            $fileHandle = fopen($file, $type);
            fwrite($fileHandle, $content);
            fclose($fileHandle);
        } catch (ErrorException $e) {
            Logger::error('ERR_FILE_WRITE', [$file, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_FILE_WRITE, PRISM_MSG[PrismCode::ERR_FILE_WRITE], $e);
        }
    }

    /**
     * @param     $path
     * @param int $dirPerm
     *
     * @desc 创建文件夹
     */
    public static function makeDir($path, $dirPerm = 0) {
        try {
            system("mkdir $path");
            if ($dirPerm) {
                system("chmod -R $dirPerm" . " $path");
            }
        } catch (ErrorException $e) {
            Logger::error('ERR_MAKE_DIR', [$path, $e->getMessage()]);
            Response::sendException(PrismCode::ERR_MAKE_DIR, PRISM_MSG[PrismCode::ERR_MAKE_DIR], $e);
        }
    }
}