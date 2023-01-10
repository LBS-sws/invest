<?php
	$ftrbtn = array();
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnWFClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'agreementdialog',
					'header'=>Yii::t('contract','Supplemental Agreement'),
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="box" id="flow-list" style="max-height: 300px; overflow-y: auto;">
	<table id="tblFlow" class="table table-hover">
		<thead>
			<tr>
                <th></th>
                <th><?php echo Yii::t("contract","Agreement File"); ?></th>
                <th><?php echo Yii::t("dialog","Date"); ?></th>
			</tr>
		</thead>
		<tbody>

        <?php
        $staffAgreement = $model->staffHasAgreement();
        if(!empty($staffAgreement)){
            $sum = count($staffAgreement);
            foreach ($staffAgreement as $num => $agreement){
                echo "<tr>";
                echo "<td class='text-center'>";
                echo TbHtml::link("<span class='fa fa-download'></span>",Yii::app()->createUrl('employee/downAgreement',
                    array("index"=>$agreement["id"],"staff"=>$agreement["employee_id"])),array("target"=>"_blank"));
                echo "</td>";
                echo "<td>".Yii::t("contract","Supplemental Agreement")." ".($sum-$num)."</td>";
                echo "<td>".$agreement["lcd"]."</td>";
                echo "</tr>";
            }
        }
        ?>
		</tbody>
	</table>
</div>

<?php
	$this->endWidget();
?>
