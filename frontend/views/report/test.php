<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\models\Smeta;
use common\models\OutputValue;
use common\models\EstimateStage;
use backend\utils\D;
use yii\helpers\Html;
use PhpOffice\PhpSpreadsheet\Style\Border;


/* @var $outputValue \common\models\OutputValue */
/* @var $stage \common\models\EstimateStage */
/* @var $estimate \common\models\Estimate */
/* @var $smeta \common\models\Smeta */

$borders = [
    'bottom' =>
        [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['rgb' => '808080']
        ],
    'top' =>
        [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['rgb' => '808080']
        ],
    'right' =>
        [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['rgb' => '808080']
        ],
    'left' =>
        [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['rgb' => '808080']
        ]
];
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->getColumnDimension('A')->setWidth(60);
$sheet->getColumnDimension('B')->setWidth(60);
$sheet->getStyle("A1")->getFont()->setSize(14);
$sheet->getStyle("A")->getFont()->setBold(false);



$index = 0;

if ($smeta_id) $smeta = Smeta::findOne($smeta_id);
else  $smeta = Smeta::findOne(3);
if ($estimates = $smeta->estimates) {
    foreach ($estimates as $estimate) {
        D::success("ESTIMATE_NAME = " . $estimate->name);
        echo "<table class= 'table table-bordered table-striped'>";
        if ($stages = $estimate->stages) {
            foreach ($stages as $stage) {
                echo Html::tag('tr', Html::tag('td', Html::tag("h4", $stage->name)));
                \backend\utils\D::success("-->>> STAGE NAME = " . $stage->name);
                $index++;
                $sheet->setCellValue("A" . $index, $stage->name);
                $sheet->getStyle("A".$index)->getFont()->setSize(14);
                $sheet->getStyle("A".$index)->getFont()->setBold(true);
                $sheet->getStyle("A".$index.":B".$index)->getBorders()->applyFromArray($borders);

                $outputsValues = OutputValue::find()->where(['stage_id' => $stage->stage_id])->all();
                if ($outputsValues) {
                    foreach ($outputsValues as $key => $outputValue) {
                        \backend\utils\D::success("----->>>> OUTPUT_NAME = " . $outputValue->output->name . " VALUE " . $outputValue->value);
                        $index++;
                        echo Html::tag('tr', Html::tag('td', $outputValue->output->name) . Html::tag('td', $outputValue->value));
                        $sheet->getRowDimension($index)->setRowHeight(18);
                        $sheet->getStyle("A".$index)->getBorders()->applyFromArray($borders);
                        $sheet->getStyle("B".$index)->getBorders()->applyFromArray($borders);
                        $sheet->getStyle("B".$index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                        $sheet->setCellValue("A" . $index, $outputValue->output->name);
                        $sheet->setCellValue("B" . $index, $outputValue->value);
                    }
                }
            }
        };
        echo "</table>";
    }

}

//   \backend\utils\D::dump($stages);


$writer = new Xlsx($spreadsheet);
$writer->save('hello_world.xlsx');

$class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
\PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');

$writer->save('hello_world.pdf');



