<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 12:18
 */

namespace common\models\evaluator;

class EvaluatorBooleanRequest extends EvaluatorRequest
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

    private function compose()
    {
        if ($this->formula) $this->body = $this->formula . ";" . PHP_EOL . "if (" . $this->response . ") return 1; else return 0;";
        else $this->body = "if (" . $this->response . ") return 1; else return 0;";
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