<?php

class ExcelToolEx extends ExcelTool {
	public function setColumnWidth($col, $width) {
		$this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth($width);
	}
	
	public function mergeCells($row1, $col1, $row2, $col2) {
		$column1 = $this->getColumn($col1);
		$column2 = $this->getColumn($col2);
		$range = $column1.$row1.':'.$column2.$row2;
		$this->objPHPExcel->getActiveSheet()->mergeCells($range);
	}
	
	public function setHeaderStyle($row1, $col1, $row2, $col2) {
		$styleArray = array(
			'font'=>array(
				'bold'=>true,
			),
			'alignment'=>array(
				'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'borders'=>array(
				'outline'=>array(
					'style'=>PHPExcel_Style_Border::BORDER_THIN,
				),
			),
			'fill'=>array(
				'type'=>PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor'=>array(
					'argb'=>'AFECFF',
				),
			),
		);
		$column1 = $this->getColumn($col1);
		$column2 = $this->getColumn($col2);
		$range = $column1.$row1.':'.$column2.$row2;
		$this->objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($styleArray);
	}
	
	public function setGroupHeaderStyle($row1, $col1, $row2, $col2) {
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col1,$row1)->getFont()
			->setBold(true)
			->setItalic(true);
		$this->objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col1,$row1)->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('AFECFF');
		$this->mergeCells($row1, $col1, $row2, $col2);
	}
}
?>