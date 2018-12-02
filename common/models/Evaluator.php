<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 02.12.2018
 * Time: 8:50
 */

namespace common\models;


use backend\utils\D;

class Evaluator
{

    public static $formulaPattern = '/[*|\/|-|+|round|abs|floor|\|\||&&]/';

    public static function make($result)
    {
        if (preg_match(self::$formulaPattern, $result)) {
            $eval = "; return " . $result . ";";

            try {
                $response['value'] = eval($eval);
                $response['type'] = 'success';

            } catch (\ParseError $e) {
                $response['type'] = 'danger';
                $response ['value'] = $result;
                $response['message'] = $e->getMessage() . " in " . $e->getCode() . " at line " . $e->getLine();

            }

        } else {
            $response['value'] = $result;
            $response['type'] = 'success';

        }

        return $response;


    }

    public static function makeBoolean($result)
    {
        if (preg_match(self::$formulaPattern, $result)) {
            $eval = "if (" . $result . ") return 1; else return 0;";
           // D::success($eval);
            try {
              //  D::dump(eval($eval));
                $response['value'] = eval($eval);
                $response['type'] = 'success';

            } catch (\ParseError $e) {
                $response['type'] = 'danger';
                $response ['value'] = $result;
                $response['message'] = $e->getMessage() . " in " . $e->getCode() . " at line " . $e->getLine();

            }

        } else {
            $response['value'] = $result;
            $response['type'] = 'success';

        }

        return $response;


    }


}