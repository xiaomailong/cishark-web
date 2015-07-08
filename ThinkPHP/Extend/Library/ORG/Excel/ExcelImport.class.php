<?php

class ExcelImport{
	private $excelObj;
	private $sheelCount,$curSheelIndex,$curTitle,$currentSheet ;
	private $minRow = 1,$maxRow,$minCol =0,$maxCol  ;
	private $key ;
	public function open($fileName){
		set_time_limit(0);
		import("ORG.Excel.PHPExcel") ;
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
		$this->excelObj = PHPExcel_IOFactory::load($fileName) ;
		$this->sheelCount = $this->excelObj->getSheetCount() ;
		if($this->sheelCount>0){
			$this->setActiveSheet(0);
			$this->setKey() ;
		}
	}
	
	public function setActiveSheet($index){
		$this->curSheelIndex = $index ;
		$this->currentSheet = $this->excelObj->setActiveSheetIndex($this->curSheelIndex);
		$this->curTitle = $this->currentSheet->getTitle() ;
		$this->maxRow = $this->currentSheet->getHighestRow() ;
		$higCol = $this->currentSheet->getHighestColumn() ;
		$this->maxCol = PHPExcel_Cell::columnIndexFromString($higCol);
	}
	public function setNextSheet(){
		$this->curSheelIndex++ ;
		if( $this->curSheelIndex < $this->sheelCount ){
			$this->setActiveSheet($this->curSheelIndex) ;
			return true ;
		}else{
			$this->curSheelIndex-- ;
			return false ;
		}
	}
	public function setKey( $key = NULL ){
		if( $key ){
			$this->key = $key ;
		}else{
			for( $i = $this->minCol ; $i < $this->maxCol ; $i++ ){
				$this->key[$i] = $this->currentSheet->getCellByColumnAndRow( $i ,$this->minRow )->getCalculatedValue();
			}
		}
	}
	public function getActiveSheetTitle(){
		return $this->curTitle ;
	}
	//谨慎操作，调用此接口可能导致本类其他操作不可预想结果
	public function getPhpExcelObj(){
		return $this->excelObj ;
	}

	public function getNextData(){
		$result ;
		if( $this->minRow < $this->maxRow ){
			$this->minRow++ ;
			for( $i = $this->minCol ; $i < $this->maxCol ; $i++ ){
				if( $this->currentSheet->cellExistsByColumnAndRow( $i ,$this->minRow )){
					$result[$this->key[$i]] = $this->currentSheet->getCellByColumnAndRow( $i ,$this->minRow )->getCalculatedValue();
				}else{
					$result[$this->key[$i]] = NULL ;
				}
			}
		}else{
			$this->minRow-- ;
			$result = NULL ;
		}
		return $result ;
	}
	
}

?>
