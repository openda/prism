<?php
/**
 * Created by PhpStorm.
 * User: wangxiaokang@iwaimai.baidu.com
 * Date: 17/3/29
 * Time: 下午2:50
 * Desc:
 */

namespace prism;


class Logger {

    const DEBUG  = 0;
    const NOTICE = 1;
    const WARN   = 2;
    const ERROR  = 3;

    static $LogLevel = array(
        Logger::DEBUG  => "DEBUG",
        Logger::NOTICE => "NOTICE",
        Logger::WARN   => "WARN",
        Logger::ERROR  => "ERROR",
    );

    static $in     = array();
    static $out    = array();
    static $result = array();

    static $visitTime = null;
    static $visitId   = null;

    static public function setVisitTime($vtime) {
        self::$visitTime = $vtime;
    }

    static public function setVisitId($vid) {
        self::$visitId = $vid;
    }

    static public function setInItem($key, $value) {
        self::$in[$key] = $value;
    }

    static public function setOutItem($key, $value) {
        self::$out[$key] = $value;
    }

    static public function getResultItem(array $result) {
        if (key_exists("errNo", $result)) {
            self::$result["errNo"] = $result["errNo"];
        }
        if (key_exists("errMsg", $result)) {
            self::$result["errMsg"] = $result["errMsg"];
        }
    }


    static public function visitLog() {
        $log   = array();
        $log[] = self::$in;
        $log[] = self::$result;
        $log[] = self::$out;

        $str = ' ';
        $sep = "[";
        foreach (self::$in as $key => $value) {
            $str = $str . $sep . $key . ":" . $value;
            $sep = "|";
        }
        $str = $str . ']';

        $sep = "[";
        foreach (self::$out as $key => $value) {
            $str = $str . $sep . $key . ":" . $value;
            $sep = "|";
        }
        $str = $str . ']';

        $sep = "[";
        foreach (self::$result as $key => $value) {
            $str = $str . $sep . $key . ":" . $value;
            $sep = "|";
        }
        $str = $str . ']';

        Logger::log(Logger::NOTICE, $str);
    }


    static protected function getFileName($logLevel) {
        $logfile  = Config::get("log_file");
        $date     = date('Y-m-d');
        $filename = null;
        if ($logLevel == Logger::DEBUG || $logLevel == Logger::NOTICE) {
            $filename = LOG_PATH . '/' . $logfile . '.' . $date;
        } elseif ($logLevel == Logger::WARN || $logfile == Logger::ERROR) {
            $filename = LOG_PATH . '/' . $logfile . '.' . $date . '.warn';
        } else {
            $filename = null;
        }

        return $filename;
    }


    static public function debugForSql($sql, $values) {
        $conf  = ClassLoader::getConfigure();
        $level = $conf["logLevel"];

        if ($level != "DEBUG") {
            return;
        }
        $seps   = explode('?', $sql);
        $values = array_values($values);

        $index = 0;
        $log   = " SQL: ";

        $values = array_values($values);
        if (is_array($seps) && !empty($seps)) {
            foreach ($seps as $sep) {
                $log = $log . $sep;
                if (count($values) > $index) ;
                $log = $log . '\'' . $values[$index] . '\'';
                $index++;
            }
        }
        $log = str_replace("''", '', $log);
        Logger::log(Logger::DEBUG, $log);
    }


    static public function debug($logText, $infos = []) {
        if (!APP_DEBUG) {
            return;
        }
        $log = self::make($logText, $infos);
        Logger::log(Logger::DEBUG, $log);
    }


    static public function warn($logText, $infos = []) {
        $log = self::make($logText, $infos);
        Logger::log(Logger::WARN, $log);
    }


    static public function error($logText, $infos = []) {
        $log = self::make($logText, $infos);
        Logger::log(Logger::ERROR, $log);
    }

    static private function make($textType, $infos = []) {
        $log = '';
        $prismLog = Config::get('prism_log');
        $appLog   = Config::get('app_log');
        $logText  = empty($prismLog[$textType]) ? $appLog[$textType] : $prismLog[$textType];
        if (empty($logText)) {
            $trace      = debug_backtrace();
            $logContent = "[LOG-DETAIL] : not define log text in configure file！\n";
            $logContent = $logContent . "[LOG-HAPPEN] : " .
                "Class:{$trace[2]['class']} | Function:{$trace[2]['function']} | Line:{$trace[1]['line']} | File:{$trace[1]['file']}" . "\n";
        } else {
            $logTexts = explode('%s', $logText);
            $trace    = debug_backtrace();
            for ($i = 0; $i < count($logTexts); $i++) {
                $log = $log . $logTexts[$i] . (empty($infos[$i]) ? '' : $infos[$i]);
            }
            $logContent = "[LOG-DETAIL] : " . $log . "\n";
            $logContent = $logContent . "[LOG-HAPPEN] : " .
                "Class:{$trace[2]['class']} | Function:{$trace[2]['function']} | Line:{$trace[1]['line']} | File:{$trace[1]['file']}" . "\n";
        }

        return $logContent;
    }

    static public function log($level, $str) {
        if (self::$visitTime == null)
            self::$visitTime = date('Y-m-d H:i:s');

        if (self::$visitId == null)
            self::$visitId = rand(1000, 10000);

        $levelStr = self::$LogLevel[$level];

        $str = "[{$levelStr}][" . self::$visitId . "][" . self::$visitTime . "]\n" . $str . "\n";

        $logfile = Logger::getFileName($level);
//        Response::outputPage($logfile, 1);
        file_put_contents($logfile, $str, FILE_APPEND);
    }
}