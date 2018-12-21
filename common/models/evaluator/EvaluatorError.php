<?php
/**
 * Created by PhpStorm.
 * User: anvik
 * Date: 19.12.2018
 * Time: 21:21
 */

namespace common\models\evaluator;


class EvaluatorError
{
    private $parseError;
    private $request;
    private $response;

    public function __construct(\ParseError $parseError,EvaluatorRequest $request)
    {
        $this->request = $request;
        $this->parseError = $parseError;
        $this->preg_match_error();

    }
        public function preg_match_error() {
        if (preg_match('/syntax/',$this->parseError->getMessage())) {
            $this->response = 'Синтаксическая ошибка';
        }
    }

    public function __toString()
    {
       return $this->response;
    }
}