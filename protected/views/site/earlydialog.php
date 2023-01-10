<?php
	$ftrbtn = array();
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnEARLYClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_DEFAULT,"class"=>"pull-left"));
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','OK'), array('id'=>'btnEARLYSubmit','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'earlydialog',
					'header'=>"<span id='early_head'>......</span>",
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="form-group">
    <?php echo $form->labelEx($model,"early_date",array('class'=>"col-sm-3 control-label","id"=>"early_date_label")); ?>
    <div class="col-sm-8">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <?php echo $form->textField($model, 'early_date',
                array('class'=>'form-control','id'=>"early_date"));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo $form->labelEx($model,"early_remark",array('class'=>"col-sm-3 control-label","id"=>"early_remark_label")); ?>
    <div class="col-sm-8">
        <?php echo $form->textArea($model, "early_remark",
            array('rows'=>4,'cols'=>50,'maxlength'=>1000)
        ); ?>
    </div>
</div>

<?php
	$this->endWidget(); 
?>
