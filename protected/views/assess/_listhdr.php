<tr>
    <?php if (Yii::app()->user->validRWFunction('ZE07')): ?>
        <th></th>
    <?php endif; ?>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('employee_code').$this->drawOrderArrow('b.code'),'#',$this->createOrderLink('assess-list','b.code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('employee_name').$this->drawOrderArrow('b.name'),'#',$this->createOrderLink('assess-list','b.name'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('city').$this->drawOrderArrow('b.city'),'#',$this->createOrderLink('assess-list','b.city'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('work_type').$this->drawOrderArrow('a.work_type'),'#',$this->createOrderLink('assess-list','a.work_type'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('staff_type').$this->drawOrderArrow('a.staff_type'),'#',$this->createOrderLink('assess-list','a.staff_type'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('service_effect').$this->drawOrderArrow('a.service_effect'),'#',$this->createOrderLink('assess-list','a.service_effect'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('lcu').$this->drawOrderArrow('a.lcu'),'#',$this->createOrderLink('assess-list','a.lcu'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('lcd').$this->drawOrderArrow('a.lcd'),'#',$this->createOrderLink('assess-list','a.lcd'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('email_bool').$this->drawOrderArrow('a.email_bool'),'#',$this->createOrderLink('assess-list','a.email_bool'))
			;
		?>
	</th>
</tr>
