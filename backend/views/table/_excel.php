<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\models\Smeta;
use common\models\OutputValue;
use common\models\EstimateStage;
use backend\utils\D;
use yii\helpers\Html;
use PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style;


/* @var $outputValue \common\models\OutputValue */
/* @var $table \common\models\Table */
/* @var $table_column \common\models\TableColumns */
/* @var $stage \common\models\EstimateStage */
/* @var $estimate \common\models\Estimate */
/* @var $smeta \common\models\Smeta */
/* @var $cell \common\models\TableCells */
/* @var $row \common\models\TableRows */

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

$smeta = Smeta::forTest();
$smeta->loadVariables();


$table = \common\models\Table::findOne($table_id);

$estimate = \common\models\Estimate::findOne($table->estimate_id);
$inputs = $estimate->inputsAll;
$events = $estimate->events;
$outputs = $estimate->outputs;

if ($table_columns = $table->columns) {
    foreach ($table_columns as $table_column) {
        $letter = \common\models\TableCells::$letters[$table_column->td_id - 1];
        $width = round($table_column->width / 6);
        if ($width < 10) $width = 10;
        $sheet->getColumnDimension($letter)->setWidth($width);
        $widths[$table_column->td_id] = $width;
        // D::success("SETTING COLUMN ".$letter."  WIDTH".$table_column->width);
    }
}

/*$sheet->getStyle("A1")->getFont()->setSize(14);
$sheet->getStyle("A")->getFont()->setBold(false);*/

$currentRow = 0;
$index = 0;
if ($rows = $table->rows) {
    foreach ($rows as $row) {
        if ($row->isActive($smeta)) {
            $currentRow++;
            $countRowsMax = 1;
            if ($cells = $row->cells) {
                foreach ($cells as $cell) {
                    $column = preg_replace("/\d+/", "", $cell->address);
                    $address = $column . $currentRow;
                    $response = \common\models\Evaluator::make($smeta->ReplaceValue($cell->value), $cell->type);
                    $smeta->addVariables([$cell->address => $response['value']]);

                    $sheet->getStyle($address)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                    if (preg_match("/" . \common\models\TableCells::H4 . "/", $cell->classes)) $sheet->getStyle($address)->getFont()->setSize(18);
                    if (preg_match("/" . \common\models\TableCells::H1 . "/", $cell->classes)) $sheet->getStyle($address)->getFont()->setSize(24);
                    if (preg_match("/" . \common\models\TableCells::BOLD . "/", $cell->classes)) $sheet->getStyle($address)->getFont()->setBold(true);
                    if (preg_match("/" . \common\models\TableCells::FILL_BLUE . "/", $cell->classes)) {
                       // $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor('FF0000')->setEndColor("FF0000");
                        $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB(Style\Color::COLOR_BLUE);
                    }
                    if (preg_match("/" . \common\models\TableCells::FILL_GREEN . "/", $cell->classes)) {

                       // $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor('FF0000')->setEndColor("FF0000");
                        $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB(Style\Color::COLOR_GREEN);
                    }
                    if (preg_match("/" . \common\models\TableCells::FILL_RED . "/", $cell->classes)) {
                       // $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor('FF0000')->setEndColor("FF0000");
                        $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
                    }
                    /*  if (preg_match("/" . \common\models\TableCells::FILL_GREEN . "/", $cell->classes))  $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor()->setEndColor("4d803b");
                      if (preg_match("/" . \common\models\TableCells::FILL_BLUE . "/", $cell->classes))  $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor()->setEndColor("46dbff");*/


                    $sheet->getStyle($address)->getBorders()->applyFromArray($borders);
                    if (preg_match("/" . \common\models\TableCells::RIGHT . "/", $cell->classes)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    if (preg_match("/" . \common\models\TableCells::CENTER . "/", $cell->classes)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    if (preg_match("/" . \common\models\TableCells::LEFT . "/", $cell->classes)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $countRows = \common\models\TableCells::countRows($response['value'], $widths[$cell->td_id]);
                    if ($countRows > $countRowsMax) $countRowsMax = $countRows;
                    $sheet->setCellValue($address, $response['value']);
                    $sheet->getStyle($address)->getAlignment()->setWrapText(true);
                }

            }
            $sheet->getRowDimension($countRowsMax)->setRowHeight(18 * $countRowsMax);

        } else {
            if ($cells = $row->cells) {
                $smeta->addVariables([$cell->address => 0]);

            }
        }

    }

}


//   \backend\utils\D::dump($stages);


$writer = new Xlsx($spreadsheet);
$writer->save(date("d_m_y_h_i_s_A") . ".xlsx");

$class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
\PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');

$writer->save(date("d_m_y_h_i_s_A") . ".pdf");



