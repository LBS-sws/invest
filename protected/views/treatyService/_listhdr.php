<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('treaty_code').$this->drawOrderArrow('a.treaty_code'),'#',$this->createOrderLink('treatyService-list','a.treaty_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_name').$this->drawOrderArrow('a.company_name'),'#',$this->createOrderLink('treatyService-list','a.company_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('city_allow').$this->drawOrderArrow('b.name'),'#',$this->createOrderLink('treatyService-list','b.name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('treaty_num').$this->drawOrderArrow('a.treaty_num'),'#',$this->createOrderLink('treatyService-list','a.treaty_num'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('start_date').$this->drawOrderArrow('a.start_date'),'#',$this->createOrderLink('treatyService-list','a.start_date'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('end_date').$this->drawOrderArrow('a.end_date'),'#',$this->createOrderLink('treatyService-list','a.end_date'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('state_type').$this->drawOrderArrow('a.state_type'),'#',$this->createOrderLink('treatyService-list','a.state_type'))
			;
		?>
	</th>
	<th width="3%">
		<?php echo TbHtml::link("".$this->drawOrderArrow('treaty'),'#',$this->createOrderLink('treatyService-list','treaty'))
			;
		?>
	</th>
</tr>
