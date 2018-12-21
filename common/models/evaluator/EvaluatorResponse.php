<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 12:18
 */

namespace common\models\evaluator;

use backend\utils\D;

class EvaluatorResponse
{

    private $request;
    private $error;
    private $status = true;
    private $response = '';

    public function __construct(EvaluatorRequest $request, $response = false, EvaluatorError $error = null)
    {
        if ($response !== false) {
            $this->response = $response;
            $this->status = true;
        } else {
            $this->status = false;
        }

        $this->request = $request;
        if ($error) $this->error = $error;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setError($insert)
    {
        $this->error = $insert;
    }

    public function __toString()
    {

        /* $var $error  \ParseError */

        if ($this->status) {
            if ($this->response === '') $this->response = '0';
            return $this->request . PHP_EOL . $this->response;
        } else {
            $error = $this->error;
            /* $var $error  EvaluatorError  */
             D::alert($error);
            return "<--ERROR--> " . PHP_EOL . $this->request . PHP_EOL.$error;
        }
    }

}