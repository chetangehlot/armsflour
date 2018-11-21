<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Createxls extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('excel');
    }

    public function excel($data = null, $column_key_value = null) {
        $this->excel->setActiveSheetIndex(0);
//name the worksheet
        $this->excel->getActiveSheet()->setTitle('Countries');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'S.No.');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Product Name');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Description');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Full Price');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Half Price');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Discount');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Discount Full Price');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Discount Half Price');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Discount Half Price');
        $this->excel->getActiveSheet()->setCellValue('J1', 'Discount Half Price');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Discount Half Price');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Strat Time');
        $this->excel->getActiveSheet()->setCellValue('M1', 'End Time');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Status');
        //merge cell A1 until C1
        //set aligment to center for that merged cell (A1 to C1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
        for ($col = ord('A'); $col <= ord('N'); $col++) { //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
            //change the font size
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);

            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
//retrive contries table data

        $exceldata = array();

//Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A1');

        $filename = 'fkm_xls.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */