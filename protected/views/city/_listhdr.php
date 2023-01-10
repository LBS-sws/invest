<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('city_name').$this->drawOrderArrow('a.code'),'#',$this->createOrderLink('city-list','a.code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('z_index').$this->drawOrderArrow('b.z_index'),'#',$this->createOrderLink('city-list','b.z_index'))
			;
		?>
	</th>
</tr>
