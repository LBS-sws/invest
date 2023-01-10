<?php
	$ftrbtn = array();
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnWFClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'flowinfodialog',
					'header'=>Yii::t('dialog','Flow Info'),
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="box" id="flow-list" style="max-height: 300px; overflow-y: auto;">
	<table id="tblFlow" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
                <th><?php echo Yii::t("contract","Employee Code"); ?></th>
                <th><?php echo Yii::t("contract","Employee Name"); ?></th>
                <th><?php echo Yii::t("contract","Operator User"); ?></th>
                <th><?php echo Yii::t("contract","Operator Time"); ?></th>
                <th><?php echo Yii::t("contract","Operation Status"); ?></th>
                <th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>

        <?php
        if(!empty($model->historyList)){
            foreach ($model->historyList as $staff){
                echo "<tr><td>".$staff['code']."</td><td>".$staff['name']."</td><td>".$staff['lcu']."</td><td>".$staff['lcd']."</td>";
                echo "<td>".Yii::t("contract",$staff['status']);
                if(!empty($staff['num'])){
                    echo $staff['num'];
                }
                echo "</td>";
                echo "<td>";
                if(!empty($staff['history_id'])){
                    echo "<a target='_blank' href='".Yii::app()->createUrl('history/detail',array('index'=>$staff['history_id'],'type'=>'view'))."'><span class='glyphicon glyphicon-eye-open'></span></a>";
                }
                echo "</td></tr>";
            }
        }
        ?>
		</tbody>
	</table>
</div>

<?php
	$this->endWidget();
?>
