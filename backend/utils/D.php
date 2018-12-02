<?php

namespace backend\utils;

class D
{

    const LOG_TO_FILE = true;
    const PATH_TO_JSON = "debug/log.json";
    public static $isConsole = false;
    public static $isLogToFile = false;


    private static $dumpVars;

    public static function logToFile($var)
    {

        $log = $var . "\r\n";

     if (self::$isLogToFile) file_put_contents(self::PATH_TO_JSON, $log, FILE_APPEND);
    }

    public static function dumpToFile($var)
    {

        $log = file_get_contents(self::PATH_TO_JSON);
        $log .= json_encode($var) . "\r\n";

        if (self::$isLogToFile) file_put_contents(self::PATH_TO_JSON, $log);
    }

    public static function dump($var)
    {
        if (self::$isConsole) {
            print_r($var);
        }
        ob_start();
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        if (self::$isLogToFile)  static::dumpToFile($var);

        static::$dumpVars[] = ob_get_clean();
    }

    public static function echor($var, $br = true)
    {
        ob_start();
        if ($br) echo "<br>" . $var; else echo $var;
        static::logToFile($var);
        static::$dumpVars[] = ob_get_clean();
    }

    public static function info($var)
    {
        ob_start();
        echo "<div class=\"alert-my alert-primary\">" . $var . "</div>";
        static::$dumpVars[] = ob_get_clean();
    }

    public static function alert($var,$type = 'danger')
    {
        if (self::$isConsole) {
            self::toConsole($var,'danger');
        }

        ob_start();
        echo "<div class=\"alert-my alert-danger\">" . $var . "</div>";
        static::logToFile($var);
        static::$dumpVars[] = ob_get_clean();
    }

    public static function success($var)
    {
        if (self::$isConsole) {
            self::toConsole($var,'success');
        }

        ob_start();
        echo "<div class=\"alert-my alert-success\">" . $var . "</div>";
        static::$dumpVars[] = ob_get_clean();
        static::logToFile($var);
    }
    public static function primary($var)
    {
        if (self::$isConsole) {
            self::toConsole($var,'primary');
        }

        ob_start();
        echo "<div class=\"alert-my alert-primary\">" . $var . "</div>";
        static::$dumpVars[] = ob_get_clean();
        static::logToFile($var);
    }

    public static function alert_no($var, $type = "primary")
    {
        echo "<div class=\"alert alert-" . $type . "\">" . $var . "</div>";

    }

    public static function printr()
    {
        if (YII_DEBUG && isset(static::$dumpVars)) {
            foreach (static::$dumpVars as $var) {
                echo $var;
            }
        }
    }

    public static function renderProperties($model, $properties)
    {

        // распечатывание свойств
        foreach ($properties as $property) {
            {
                D::echor($property . " = <b>" . $model[$property] . "</b>");

            }

        }
        D::echor("<hr> ");

    }

    public static function toConsole($message = "Hello", $type = '') {


            $message = strip_tags($message);
            switch ($type) {
                case 'danger':
                    echo "\033[1;31m" . strip_tags($message) . "\033[0m\n";
                    break;
                case 'success':
                    echo "\033[1;32m" . strip_tags($message) . "\033[0m\n";
                    break;
                case 'warning':
                    echo "\033[1;33m" . strip_tags($message) . "\033[0m\n";
                    break;
                case 'primary':
                    echo "\033[1;34m" . strip_tags($message) . "\033[0m\n";
                    break;
                default:
                    echo $message . "\n";
                    break;
            }


        }

}