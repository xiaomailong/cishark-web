<?php

class ExcelExport {
	private $excelObj,$activeSheet;
	private $minRow = 1,$maxRow,$minCol =0,$maxCol  ;
	private $key ;
	public function creatExcel(){
		set_time_limit(0);
		import("ORG.Excel.PHPExcel") ;
		$this->excelObj = new PHPExcel() ;
		$this->activeSheet = $this->excelObj->setActiveSheetIndex(0);
	}
	public function writeArray($data , $header=NULL){
		if($header == NULL){
			$header = $data[0] ;
			foreach ($header as $key => $value) {
				$header[$key] = $key;
			}
		}
		//注册表头位置，并且写表头
		$i = 0 ;
		if(is_array($header)){
			foreach ($header as $key => $value) {
				$this->key[$key] = $i ;
				$this->activeSheet->setCellValueByColumnAndRow( $i, $this->minRow, $value) ;
				$this->activeSheet->getStyleByColumnAndRow($i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT) ;
				$this->activeSheet->getColumnDimensionByColumn($i)->setAutoSize(true) ;
				$i++ ;
			}
			$this->minRow++ ;
		}
		//从表头读取数据，写入excel
		foreach ($data as  $curdata) {
			foreach($this->key as $key => $value){
				$this->activeSheet->setCellValueByColumnAndRow( $value, $this->minRow, $curdata[$key]) ;
			}
			$this->minRow++ ;
		}
	}
	/*
	 * $type值可以为Excel2007，Excel5，PDF
	 */
	public function save($fileName,$type="Excel2007"){
		$objWrite = PHPExcel_IOFactory::createWriter($this->excelObj,$type) ;
		return $objWrite->save($fileName) ;
	}
	public function outPut($fileName,$type="Excel2007"){
		$ok = true ;
		switch ($type) {
			case "Excel5":
				$conType ="vnd.ms-excel";
				break;
			case "PDF":
				$ok = false ;
				$conType ="pdf";
				break;
			case "Excel2007":
			default:
				$conType ="vnd.openxmlformats-officedocument.spreadsheetml.sheet";
				break;
		}
		if($ok){
			ob_clean();
			header("Content-Type: application/$conType");
			header('Content-Disposition: attachment;filename="'.$fileName.'"');
			header('Cache-Control: max-age=0');
			$this->save("php://output", $type) ;
			ob_end_flush() ;
		}
		return $ok ;
	}
}

?>
