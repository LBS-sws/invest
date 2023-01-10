<?php
	$ftrbtn = array();
	//$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnWFClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'helpdialog',
					'header'=>"",
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="col-sm-12">
    <div class="row">
        <?php
        echo $helpHtml;
        ?>
    </div>
</div>

<?php
	$this->endWidget(); 
?>
