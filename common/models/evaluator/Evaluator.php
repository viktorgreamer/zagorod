<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 12:13
 */

namespace common\models\evaluator;

use backend\utils\D;

class Evaluator
{

    private static $formulaPattern = '/^=/';


    static function make(EvaluatorRequest $request): EvaluatorResponse
    {
        try {
            $response = eval($request->getBody());
            return new EvaluatorResponse($request, $response);
        } catch (\ParseError $error) {
            return new EvaluatorResponse($request, false, new EvaluatorError($error,$request));
        }
    }





}