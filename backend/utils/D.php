<?

namespace backend\utils;

class D
{
    const LOG_TO_FILE = true;

    private static $dumpVars;

    public static function logToFile($var)
    {

        $log = file_get_contents("log.json");
        $log .= $var . "\r\n";

        file_put_contents('log.json', $log);
    }

    public static function dumpToFile($var)
    {

        $log = file_get_contents("log.json");
        $log .= json_encode($var) . "\r\n";

        file_put_contents('log.json', $log);
    }

    public static function dump($var)
    {
        ob_start();
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        static::dumpToFile($var);

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

    public static function alert($var)
    {
        ob_start();
        echo "<div class=\"alert-my alert-danger\">" . $var . "</div>";
        static::logToFile($var);
        static::$dumpVars[] = ob_get_clean();
    }

    public static function success($var)
    {
        ob_start();
        echo "<div class=\"alert-my alert-success\">" . $var . "</div>";
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
}