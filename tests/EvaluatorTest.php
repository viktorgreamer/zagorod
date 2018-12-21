<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 18.12.18
 * Time: 15:16
 */

namespace app\tests;

use app\models\evaluator\Evaluator;
use app\models\evaluator\EvaluatorRequest;
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
    public function testBodyExists()
    {
        $evaluator = Evaluator::make(new EvaluatorRequest('return 1;'));
    }
}