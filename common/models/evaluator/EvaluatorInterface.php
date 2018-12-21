<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 12:13
 */

namespace common\models\evaluator;

interface EvaluatorInterface
{
    public function make(EvaluatorRequest $request) : EvaluatorResponse;

}