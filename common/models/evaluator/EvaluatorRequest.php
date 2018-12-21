<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 12:18
 */

namespace common\models\evaluator;

class EvaluatorRequest
{
    public function __construct($response, $formula = null)
    {
        $this->response = $response;
        if ($formula) $this->formula = $formula;
        $this->compose();
    }

    private $response = '';
    private $formula = '';
    private $body = '';

    public function getBody()
    {
        return $this->body;
    }

    public function getRespose()
    {
        return $this->response;
    }

    public function getFormula()
    {
        return $this->formula;
    }

    private function compose()
    {
        if ($this->formula) $this->body = $this->formula . ";" . PHP_EOL . "return " . $this->response;
        else $this->body = "return " . $this->response;

    }


    public function setFormula($insert)
    {
        $this->formula = $insert;
    }

    public function __toString()
    {
        return "'" . $this->body . "'";
    }

}