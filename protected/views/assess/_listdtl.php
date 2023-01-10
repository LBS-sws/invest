<tr class='clickable-row<?php echo $this->record['style']; ?>' data-href='<?php echo $this->getLink('ZE07', 'assess/edit', 'assess/view', array('index'=>$this->record['id']));?>'>

    <?php if (Yii::app()->user->validRWFunction('ZE07')): ?>
        <th>
            <?php
            echo TbHtml::checkBox("AssessList[checkBoxSent][]",false,array("value"=>$this->record['id'],"class"=>"checkBoxSent"));
            ?>
        </th>
    <?php endif; ?>

    <td><?php echo $this->drawEditButton('ZE07', 'assess/edit', 'assess/view', array('index'=>$this->record['id'])); ?></td>

    <td><?php echo $this->record['employee_code']; ?></td>
    <td><?php echo $this->record['employee_name']; ?></td>
    <td><?php echo $this->record['city']; ?></td>
    <td><?php echo $this->record['work_type']; ?></td>
    <td><?php echo $this->record['staff_type']; ?></td>
    <td><?php echo $this->record['service_effect']; ?></td>
    <td><?php echo $this->record['lcu']; ?></td>
    <td><?php echo $this->record['lcd']; ?></td>
    <td><?php echo $this->record['status']; ?></td>
</tr>
