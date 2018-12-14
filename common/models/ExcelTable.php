<?php
/**
 * Created by PhpStorm.
 * User: Анастсия
 * Date: 09.12.2018
 * Time: 15:41
 */

namespace common\models;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use common\models\Table;
use common\models\TableCells;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\models\Smeta;
use common\models\OutputValue;
use common\models\EstimateStage;
use backend\utils\D;

use common\models\Evaluator;

use yii\helpers\Html;
use PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Style;


class ExcelTable extends Spreadsheet
{

    const RATIO = 6;
    const ROW_HEIGHT_RATION = 18;
    public $table_id;
    public $forClient;
    public $smeta;

    protected $widths;

    public $defaultBorders = [
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
            ]];

    public function make()
    {

        /* @var $outputValue \common\models\OutputValue */
        /* @var $table \common\models\Table */
        /* @var $table_column \common\models\TableColumns */
        /* @var $stage \common\models\EstimateStage */
        /* @var $estimate \common\models\Estimate */
        /* @var $smeta \common\models\Smeta */
        /* @var $cell \common\models\TableCells */
        /* @var $row \common\models\TableRows */


        $sheet = $this->getActiveSheet();

        $smeta = Smeta::forTest();
        $smeta->loadVariables();


        if (!$table = Table::findOne($this->table_id)) return false;

        $estimate = \common\models\Estimate::findOne($table->estimate_id);
        /* $inputs = $estimate->inputsAll;
         $events = $estimate->events;
         $outputs = $estimate->outputs;*/

        // берем все колонки для 
        if ($table_columns = $table->columns) {
            foreach ($table_columns as $table_column) {
                $letter = TableCells::$letters[$table_column->td_id - 1];
                $width = round($table_column->width / self::RATIO);

                $sheet->getColumnDimension($letter)->setWidth($width);
                $widths[$table_column->td_id] = $width;
                if ($table_column->hidden) {
                    if ($this->forClient) {

                        // $sheet->removeColumn($letter);
                    }
                    D::alert(" КОЛОНКА " . $letter . " СКРЫТА ");
                }
                // D::success("SETTING COLUMN ".$letter."  WIDTH".$table_column->width);
            }
            $this->widths = $widths;

        }

        $currentRow = 0;
        $index = 0;

        // рендерим строки
        if ($rows = $table->rows) {
            foreach ($rows as $row) {
                // если строка активна, то рендерим ее 
                if ($row->isActive($smeta)) {
                    // итерируем счетчик
                    $currentRow++;
                    // делаем переносы строк в слишком длинных ячейках
                    $countRowsMax = 1;
                    if ($cells = $row->cells) {
                        foreach ($cells as $cell) {
                            // берем колонку из адреса ячейки
                            $column = preg_replace("/\d+/", "", $cell->address);
                            // конкатенируем с текущей строкой т.е. A + 1 = A1
                            $address = $column . $currentRow;
                            // вычисляем значение ячейки 
                            $response = Evaluator::make($smeta->ReplaceValue($cell->value), $cell->type);

                            // добавлем отработанную ячейку в значения переменных в смете для дальнейшего использования в расчетах
                            $smeta->addVariables([$cell->address => $response['value']]);


                            // задаем стили ячейке согласно логике

                            $this->setStyles($address, $cell);

                            // вычисляем высоту строки в записимости от того
                            $countRows = TableCells::countRows($response['value'], $widths[$cell->td_id]);
                            if ($countRows > $countRowsMax) $countRowsMax = $countRows;
                            $sheet->setCellValue($address, $response['value']);

                        }

                        // применяем максимальную высоту текущей строки
                        $sheet->getRowDimension($countRowsMax)->setRowHeight(self::ROW_HEIGHT_RATION * $countRowsMax);

                    } else {
                        // если строка неактична, то просто вписываем 0 в значениея этих переменных для дальшейних расчетов сметы
                        if ($cells = $row->cells) {
                            $smeta->addVariables([$cell->address => 0]);

                        }
                    }

                }
            }

        }

        if ($this->forClient) {
            D::alert(" FOR CLIENT OPTION IS ACTIVATED");

            // берем все колонки для
            if ($table_columns = $table->columns) {
                foreach ($table_columns as $table_column) {
                    $letter = TableCells::$letters[$table_column->td_id - 1];

                    if ($table_column->hidden) {
                        $sheet->removeColumn($letter);
                    }

                }
            }

        }
    }

    public function saveToExcel($name = '')
    {

        if (!$name) $name = date("d_m_y_h_i_s_A") . ".xlsx";
        $writer = new Xlsx($this);
        $writer->save("export/" . $name);

    }

    public function saveToPdf($name = '')
    {

        $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
        \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this, 'Pdf');


        if (!$name) $name = date("d_m_y_h_i_s_A") . ".pdf";

        $writer->save("export/" . $name);

    }

    public function setStyles($address, TableCells $cell)
    {
        $sheet = $this->getActiveSheet();
        $styles = $cell->classes;

        /* @var $sheet */
        // задаем переносы строк в ячейке по умолчанию
        $sheet->getStyle($address)->getAlignment()->setWrapText(true);
        // задаем центрированию по вертикали по умолчанию
        $sheet->getStyle($address)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        if (preg_match("/" . \common\models\TableCells::H4 . "/", $styles)) $sheet->getStyle($address)->getFont()->setSize(18);
        if (preg_match("/" . \common\models\TableCells::H1 . "/", $styles)) $sheet->getStyle($address)->getFont()->setSize(24);
        if (preg_match("/" . \common\models\TableCells::BOLD . "/", $styles)) $sheet->getStyle($address)->getFont()->setBold(true);

       if ($cell->fillColor) $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->getStartColor()->setRGB($cell->fillColor);


       /*  if (preg_match("/" . \common\models\TableCells::FILL_GREEN . "/", $styles))  $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor()->setEndColor("4d803b");
          if (preg_match("/" . \common\models\TableCells::FILL_BLUE . "/", $styles))  $sheet->getStyle($address)->getFill()->setFillType(Style\Fill::FILL_SOLID)->setStartColor()->setEndColor("46dbff");*/


        $sheet->getStyle($address)->getBorders()->applyFromArray($this->defaultBorders);
        if (preg_match("/" . \common\models\TableCells::RIGHT . "/", $styles)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        if (preg_match("/" . \common\models\TableCells::CENTER . "/", $styles)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        if (preg_match("/" . \common\models\TableCells::LEFT . "/", $styles)) $sheet->getStyle($address)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


    }


}