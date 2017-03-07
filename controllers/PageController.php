<?php

namespace app\controllers;

use Yii;
use GuzzleHttp\Client; // подключаем Guzzle
use yii\helpers\Url;
use app\models\DataParser;
use phpexcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel;

class PageController extends \yii\web\Controller
{
    public function actionIndex()
    {
          $parser = new DataParser('IN');
          $result = $parser->getRates();

          $objPHPExcel = new \PHPExcel();

        $sheets = array_keys($result);

        foreach ($sheets as $sheet) {
            $page = $objPHPExcel->createSheet()->setTitle($sheet);
            $titles = array_keys($result[$sheet]);
            $values = array_values($result[$sheet]);

            for ($row = 1; $row <= count($titles); $row++){
                $col = 0;
                $page->setCellValueByColumnAndRow($col, $row, $titles[$row-1]);
                $col++;
                $page->setCellValueByColumnAndRow($col, $row, $values[$row-1]);
            }

        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('Rates1.xlsx');

        return $this->render('index');

    }

}
