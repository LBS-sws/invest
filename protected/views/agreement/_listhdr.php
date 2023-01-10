<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('name').$this->drawOrderArrow('name'),'#',$this->createOrderLink('agreement-list','name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('city').$this->drawOrderArrow('city'),'#',$this->createOrderLink('agreement-list','city'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('type').$this->drawOrderArrow('type'),'#',$this->createOrderLink('agreement-list','type'))
			;
		?>
	</th>
</tr>
