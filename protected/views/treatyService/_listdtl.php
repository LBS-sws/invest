<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('TH01', 'treatyService/edit', 'treatyService/view', array('index'=>$this->record['id']));?>'>
	<td><?php echo $this->drawEditButton('TH01', 'treatyService/edit', 'treatyService/view', array('index'=>$this->record['id'])); ?></td>
	<td><?php echo $this->record['treaty_code']; ?></td>
	<td class="company_name"><?php echo $this->record['company_name']; ?></td>
    <td><?php echo $this->record['city_allow']; ?></td>
    <td><?php echo $this->record['treaty_num']; ?></td>
    <td><?php echo $this->record['start_date']; ?></td>
    <td><?php echo $this->record['end_date']; ?></td>
    <td class="state_type"><?php echo $this->record['state_type']; ?></td>
    <td class="td_end" data-id="<?php echo $this->record['id']; ?>">
        <?php
        if(empty($this->record['treaty'])){
            echo "&nbsp;";
        }else{
            echo "<span class='fa fa-paperclip'></span>";
        }
        ?>
    </td>
</tr>
