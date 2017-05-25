<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
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
        $logfile  = Config::get("log_file_prefix");
        $date     = date('Y-m-d');
        $filename = null;
        if ($logLevel == Logger::DEBUG || $logLevel == Logger::NOTICE) {
            $filename = LOG_PATH . '/' . $logfile . '.' . $date;
        } elseif ($logLevel == Logger::WARN || $logLevel == Logger::ERROR) {
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


    static public function debug($desc, $info = null) {
        if (!APP_DEBUG) {
            return;
        }
        $infos = [$info];
        if (is_array($info)) {
            $infos = $info;
        }
        $log = self::make(self::DEBUG, "DEBUG", [$desc . " [ " .
                                                 str_replace('\\"', '"', json_encode($infos, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)) . " ]"]);
        Logger::log(self::DEBUG, $log);
    }


    static public function warn($logText, $info = null) {
        $infos = [$info];
        if (is_array($info)) {
            $infos = $info;
        }
        $log = self::make(self::WARN, $logText, $infos);
        Logger::log(self::WARN, $log);
    }


    static public function error($logText, $info = null) {
        $infos = [$info];
        if (is_array($info)) {
            $infos = $info;
        }
        $log = self::make(self::ERROR, $logText, $infos);
        Logger::log(self::ERROR, $log);
    }


    static public function info($logText, $info = null) {
        $infos = [$info];
        if (is_array($info)) {
            $infos = $info;
        }
        $log = self::make(self::NOTICE, $logText, $infos);
        Logger::log(self::NOTICE, $log);
    }


    static private function make($logType, $textType, $info = []) {
        $lineBreak = "\n";
        $log       = '';
        $prismLog  = Config::get('prism_log');
        $appLog    = Config::get('app_log');

        $logText = empty($prismLog[self::$LogLevel[$logType]][$textType]) ? $appLog[self::$LogLevel[$logType]][$textType] : $prismLog[self::$LogLevel[$logType]][$textType];

        if ($textType == "PRISM_END") {
            $lineBreak = "\n\n";
        }
        if (empty($logText)) {
            $trace      = debug_backtrace();
            $logContent = "[LOG-DETAIL] : not define log text in configure file！\n";
            $logContent = $logContent . "[LOG-HAPPEN] : " .
                "Class:{$trace[2]['class']} | Function:{$trace[2]['function']} | Line:{$trace[1]['line']} | File:{$trace[1]['file']}" . $lineBreak;
        } else {
            $logTexts = explode('%s', $logText);
            $trace    = debug_backtrace();
            if (!empty($info) && is_array($info)) {
                for ($i = 0; $i < count($logTexts); $i++) {
                    $log = $log . $logTexts[$i] . (empty($info[$i]) ? '' :
                            str_replace("\'", "'", json_encode($info[$i], JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)));
                }
            } else {
                $log = $log . str_replace("%s", "", str_replace("\'", "'", json_encode($logTexts, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)));
            }

            $logContent = "[LOG-DETAIL] : " . $log . "\n";
            $logContent = $logContent . "[LOG-HAPPEN] : " .
                "Class:{$trace[2]['class']} | Function:{$trace[2]['function']} | Line:{$trace[1]['line']} | File:{$trace[1]['file']}" . $lineBreak;
        }

        return $logContent;
    }


    static private function log($level, $str) {
        if (self::$visitTime == null)
            self::$visitTime = date('Y-m-d H:i:s');
//            Response::outputPage(self::$visitTime,1);

        if (self::$visitId == null) {
            self::$visitId = $GLOBALS['_loginID'];
        }

        $levelStr = self::$LogLevel[$level];

        $str = "[" . self::$visitId . "][" . $levelStr . "][" . self::$visitTime . "]\n" . $str . "\n";
//        if($level == Logger::ERROR){
//            Response::outputPage($str);
//        }
        $logfile = Logger::getFileName($level);
        file_put_contents($logfile, $str, FILE_APPEND);
    }
}