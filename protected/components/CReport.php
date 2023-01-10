<?php
class CReport {
	public $criteria;
	
	public $data = array();
	
	public $excel;
	public $title;
	public $subtitle;
	
	protected $rpt_header = array();
	protected $rpt_detail = array();
	protected $rpt_fields = array();
	protected $rpt_groups = array();

	protected $buffer_g = array();
	protected $current_row = 0;
	
	public function genReport() {
		return true;
	}

	protected function sendEmail(&$connection, $record=array()) {
		$suffix = Yii::app()->params['envSuffix'];
		$suffix1 = ($suffix=='dev') ? '_w' : $suffix;
		$sql = "insert into swoper$suffix1.swo_email_queue
					(from_addr, to_addr, cc_addr, subject, description, message, status, lcu)
				values
					(:from_addr, :to_addr, :cc_addr, :subject, :description, :message, 'P', 'admin')
			";
		$command = $connection->createCommand($sql);
		if (strpos($sql,':from_addr')!==false)
			$command->bindParam(':from_addr',$record['from_addr'],PDO::PARAM_STR);
		if (strpos($sql,':to_addr')!==false)
			$command->bindParam(':to_addr',$record['to_addr'],PDO::PARAM_STR);
		if (strpos($sql,':cc_addr')!==false)
			$command->bindParam(':cc_addr',$record['cc_addr'],PDO::PARAM_STR);
		if (strpos($sql,':subject')!==false)
			$command->bindParam(':subject',$record['subject'],PDO::PARAM_STR);
		if (strpos($sql,':description')!==false)
			$command->bindParam(':description',$record['description'],PDO::PARAM_STR);
		if (strpos($sql,':message')!==false)
			$command->bindParam(':message',$record['message'],PDO::PARAM_STR);
		$command->execute();
	}
	
	protected function sendEmailWithAttachment(&$connection, $record=array(), $attachment=array()) {
		$suffix = Yii::app()->params['envSuffix'];
		$suffix1 = ($suffix=='dev') ? '_w' : $suffix;

		$transaction=$connection->beginTransaction();
		try {
			$sql = "insert into swoper$suffix1.swo_email_queue
						(from_addr, to_addr, cc_addr, subject, description, message, status, lcu)
					values
						(:from_addr, :to_addr, :cc_addr, :subject, :description, :message, 'P', 'admin')
				";
			$command = $connection->createCommand($sql);
			if (strpos($sql,':from_addr')!==false)
				$command->bindParam(':from_addr',$record['from_addr'],PDO::PARAM_STR);
			if (strpos($sql,':to_addr')!==false)
				$command->bindParam(':to_addr',$record['to_addr'],PDO::PARAM_STR);
			if (strpos($sql,':cc_addr')!==false)
				$command->bindParam(':cc_addr',$record['cc_addr'],PDO::PARAM_STR);
			if (strpos($sql,':subject')!==false)
				$command->bindParam(':subject',$record['subject'],PDO::PARAM_STR);
			if (strpos($sql,':description')!==false)
				$command->bindParam(':description',$record['description'],PDO::PARAM_STR);
			if (strpos($sql,':message')!==false)
				$command->bindParam(':message',$record['message'],PDO::PARAM_STR);
			$command->execute();

			if (!empty($attachment)) {
				$id = $connection->getLastInsertID();
				$sql = "insert into swoper$suffix1.swo_email_queue_attm
							(queue_id, name, content)
						values
							(:queue_id, :name, :content)
					";
				foreach ($attachment as $key=>$content) {
					$command = $connection->createCommand($sql);
					if (strpos($sql,':queue_id')!==false)
						$command->bindParam(':queue_id',$id,PDO::PARAM_INT);
					if (strpos($sql,':name')!==false)
						$command->bindParam(':name',$key,PDO::PARAM_STR);
					if (strpos($sql,':content')!==false)
						$command->bindParam(':content',$content,PDO::PARAM_LOB);
					$command->execute();
				}
			}

			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			echo 'Cannot update.'.$e->getMessage();
			Yii::app()->end();
		}
	}
	
	// Abstract: Define report header label structure
	public function header_structure() {
		return array();
	}

	// Abstract: Define report detail with line structure
	public function report_structure() {
		return array();
	}

	// Abstract: Define report detail
	protected function fields() {
		return array();
	}
	
	// Abstract: Define group structure
	public function groups() {
		return array();
	}
	
	protected function exportExcel() {
		$this->excel = new ExcelToolEx();
		$this->excel->start();
		
		$this->rpt_header = $this->header_structure();
		$this->rpt_detail = $this->report_structure();
		$this->rpt_fields = $this->fields();
		$this->rpt_groups = $this->groups();
		
		$this->excel->newFile();
		if (!empty($this->sheetname)) $this->excel->getActiveSheet()->setTitle($this->sheetname);
		$this->excel->setReportDefaultFormat();
		$this->printHeader();
		$this->printDetail();
		$outstring = $this->excel->getOutput();
		
		$this->excel->end();
		return $outstring;
	}
	
	// Core function for printing report header
	protected function printHeader() {
		$title = empty($this->title) ? '' : $this->title;
		$subtitle = empty($this->subtitle) ? '' : $this->subtitle;

		$this->excel->writeReportTitle($title, $subtitle);
		if (!empty($this->rpt_header) && !empty($this->rpt_fields)) {
			$this->printHeaderWithStructure();
		} else {
			if (!empty($this->rpt_fields)) {		
				$j = 0; // column
				$this->current_row = 3;
				foreach ($this->rpt_fields as $id=>$items) {
					$this->excel->writeCell($j, $this->current_row, $items['label']);
					$this->excel->setColWidth($j, $items['width']);
					$j++;
				}

				$itemcnt = count($this->rpt_fields);
				$range = $this->excel->getColumn(0).$this->current_row.':'.$this->excel->getColumn($itemcnt-1).$this->current_row;
				$this->excel->setRangeStyle($range,true,false,'C','C','allborders',true);
			}
		}
		$this->current_row++;
	}
	
	// Print Report Header Label
	protected function printHeaderWithStructure() {
		$level = $this->getLabelLevel($this->rpt_header);
		$col = 0;
		foreach ($this->rpt_header as $item) {
			if (is_array($item)) {
				$startlvl = 1;
				$this->generateHeaderBlock($item, $col, $startlvl);
			} else {
				$this->excel->writeCell($col, $this->current_row, $this->rpt_fields[$item]['label']);
				$this->excel->setColumnWidth($col, $this->rpt_fields[$item]['width']);

				$this->excel->mergeCells($this->current_row, $col, ($this->current_row+$level-1), $col);
				$this->excel->setHeaderStyle($this->current_row, $col, ($this->current_row+$level-1), $col);
				
				$col++;
			}
		}
		$this->current_row += $level-1;
	}

	protected function generateHeaderBlock($item, &$col, $level) {
		$startcol = $col;
		
		foreach ($item['child'] as $child) {
			if (is_array($child)) {
				$this->generateHeaderBlock($child, $col, $level+1);
			} else {
				$this->excel->writeCell($col, $this->current_row+$level, $this->rpt_fields[$child]['label']);
				$this->excel->setColumnWidth($col, $this->rpt_fields[$child]['width']);
				$this->excel->setHeaderStyle(($this->current_row+$level), $col, ($this->current_row+$level), $col);
				$col++;
			}
		}
		
		$this->excel->writeCell($startcol, $this->current_row+$level-1, $item['label']);
		$this->excel->mergeCells(($this->current_row+$level-1), $startcol, ($this->current_row+$level-1), ($col-1));
		$this->excel->setHeaderStyle(($this->current_row+$level-1), $startcol, ($this->current_row+$level-1), ($col-1));
	}
	
	// Core function for printing report detail
	protected function printDetail() {
		if (!empty($this->rpt_fields) && !empty($this->data)) {		
			$itemcnt = count($this->data);
			
			// Print Detail
			if (empty($this->rpt_groups) && empty($this->rpt_detail)) $this->current_row = 4;
			
			foreach ($this->data as $row) {
				if (!empty($this->rpt_groups)) 
					$this->printGroupHeader($row);
					
				if (!empty($this->rpt_detail)) {
					$this->printLineWithStructure($row);
				} else {
					$this->printLine($row);
				}
			}
		}
	}
	
	// Print Line
	protected function printLine($data) {
		$x = 0;
		foreach ($this->rpt_fields as $key=>$items) {
			$val = $data[$key];
			$this->excel->writeCell($x, $this->current_row, $val, array('align'=>$items['align']));
			$x++;
		}
		$this->current_row++;
	}
	
	// Print Line With Structure (Using report_structure to define structure)
	protected function printLineWithStructure($data) {
		$col = 0;
		$crow = $this->current_row;
		foreach ($this->rpt_detail as $item) {
			if (is_array($item)) {
				foreach ($data['detail'] as $idx=>$row) {
					$ccol = $col;
					foreach ($item as $key) {
						$text = $row[$key];
						$this->excel->writeCell($ccol, $crow, $text, array('align'=>$this->rpt_fields[$key]['align'],'valign'=>'T'));
						$ccol++;
					}
					$crow++;
				}
				$col = $ccol;
			} else {
				$text = $data[$item];
				$this->excel->writeCell($col, $this->current_row, $text, array('align'=>$this->rpt_fields[$item]['align'],'valign'=>'T'));
				$col++;
			}
		}
		$this->current_row = $crow;
	}
	
	// Print Group Header (Using groups to define structure)
	protected function printGroupHeader($data) {
		$change = false;
		foreach ($this->rpt_groups as $idx=>$group) {
			$current = array();
			foreach ($group as $key=>$item) {
				$current[$key] = $data[$key];
			}
			$diff = array_key_exists($idx,$this->buffer_g) ? array_diff($this->buffer_g[$idx], $current) : array();
			if ($change || !array_key_exists($idx,$this->buffer_g) || !empty($diff)) {
				$change = true;
				$this->buffer_g[$idx] = $current;
				//$this->outGroupHeader($rows, $group, $idx);
				
				$col = 0;
				$totalcol = count($this->rpt_fields);
				$this->current_row++;

				foreach ($group as $key=>$def)  {
					$text = str_repeat('*',$idx).' '.$def['label'].': '.$data[$key];
					
					$this->excel->writeCell(0, $this->current_row, $text, array('align'=>'L'));
					$this->excel->setGroupHeaderStyle($this->current_row, $col, $this->current_row, $totalcol-1);
					$this->current_row++;
				}
			}
		}
	}
	
	// Get No. of Level in Header Structure
	protected function getLabelLevel($item, $level=1) {
		$rtn = $level;
		foreach ($item as $def) {
			if (is_array($def)) {
				$sublevel = isset($def['child']) ? $level+1 : $level;
				$depth = $this->getLabelLevel($def, $sublevel);
				if ($depth > $rtn) $rtn = $depth;
			}
		}
		return ($rtn==0 ? 1 :$rtn);
	}
}
?>