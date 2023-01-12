<?php
$this->pageTitle=Yii::app()->name . ' - TreatyService Form';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'TreatyService-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true,),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('treaty','Treaty Hint'); ?></strong>
	</h1>
<!--
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Layout</a></li>
		<li class="active">Top Navigation</li>
	</ol>
-->
</section>

<section class="content">
	<div class="box"><div class="box-body">
	<div class="btn-group" role="group">
		<?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
				'submit'=>Yii::app()->createUrl('treatyService/index')));
		?>
<?php if ($model->scenario!='view'): ?>
			<?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
				'submit'=>Yii::app()->createUrl('treatyService/save')));
			?>
<?php endif ?>
<?php if ($model->scenario!='new' && $model->state_type==0 && $model->scenario!='view'): ?>
	<?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
			'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
		);
	?>
<?php endif ?>
	</div>
            <div class="btn-group pull-right" role="group">
            <?php
            if ($model->scenario!='new' && $model->scenario!='view') {
                echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('treaty','Add History'), array(
                    'submit'=>Yii::app()->createUrl('treatyInfo/new',array("treaty_id"=>$model->id))));
            }
            ?>
            <?php
            $counter = ($model->no_of_attm['treaty'] > 0) ? ' <span id="doctreaty" class="label label-info">'.$model->no_of_attm['treaty'].'</span>' : ' <span id="doctreaty"></span>';

            echo TbHtml::button('<span class="fa  fa-file-text-o"></span> '.Yii::t('misc','Attachment').$counter, array(
                    'name'=>'btnFile','id'=>'btnFile','data-toggle'=>'modal','data-target'=>'#fileuploadtreaty',)
            );
            ?>
            </div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>
			<?php echo $form->hiddenField($model, 'state_type'); ?>

			<div class="form-group">
				<?php echo $form->labelEx($model,'treaty_code',array('class'=>"col-lg-2 control-label")); ?>
				<div class="col-lg-3">
				<?php echo $form->textField($model, 'treaty_code',
					array('readonly'=>(true))
				); ?>
				</div>
			</div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'city_allow',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->dropDownList($model,"city_allow",CGeneral::getCityListWithDescendant(),
                        array('readonly'=>($model->scenario=='view'),"id"=>"city_allow"));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'city',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->textField($model,"city",
                        array('readonly'=>($model->scenario=='view'),"id"=>"city"));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'company_name',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-7">
                    <?php
                    echo $form->textField($model,"company_name",
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'company_date',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->textField($model,"company_date",
                        array('readonly'=>($model->scenario=='view'),"autocomplete"=>"off",'prepend'=>'<span class="fa fa-calendar"></span>'));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'rate_government',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->numberField($model,"rate_government",
                        array('readonly'=>($model->scenario=='view'),'min'=>0,'append'=>'%'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'agent_user',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->textField($model,"agent_user",
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'agent_phone',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->textField($model,"agent_phone",
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'annual_money',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->numberField($model,"annual_money",
                        array('readonly'=>($model->scenario=='view'),'min'=>0,'append'=>Yii::t("treaty","W")));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'rate_num',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->numberField($model,"rate_num",
                        array('readonly'=>($model->scenario=='view'),'min'=>0,'append'=>'%'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'account_type',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->dropDownList($model,"account_type",TreatyServiceForm::getAccountType(),
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'technician_type',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo $form->dropDownList($model,"technician_type",TreatyServiceForm::getTechnicianType(),
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'sales_source',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-7">
                    <?php
                    echo $form->textField($model,"sales_source",
                        array('readonly'=>($model->scenario=='view')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'remark',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-7">
                    <?php
                    echo $form->textArea($model,"remark",
                        array('readonly'=>($model->scenario=='view'),'rows'=>4));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'apply_date',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'apply_date',
                        array('readonly'=>($model->scenario=='view'),'prepend'=>'<span class="fa fa-calendar"></span>')
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'apply_lcu',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'apply_lcu',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'start_date',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'start_date',
                        array('readonly'=>(true),'prepend'=>'<span class="fa fa-calendar"></span>')
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'end_date',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'end_date',
                        array('readonly'=>(true),'prepend'=>'<span class="fa fa-calendar"></span>')
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'state_type',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php
                    echo TbHtml::textField("state_type",TreatyServiceList::getStateStr($model->state_type),
                        array('readonly'=>(true)));
                    ?>
                </div>
                <?php echo $form->labelEx($model,'treaty_num',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'treaty_num',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>

            <?php if ($model->scenario!='new'): ?>
                <div class="box">
                    <div class="box-body table-responsive">
                        <legend>
                            <?php
                            echo Yii::t("treaty","treaty history")
                            ?>
                        </legend>
                        <?php
                        echo TreatyServiceForm::getHistoryTable($model->id,$model->scenario=='view')
                        ?>
                    </div>
                </div>
            <?php endif ?>
		</div>
	</div>
</section>

<?php $this->renderPartial('//site/fileupload',array('model'=>$model,
    'form'=>$form,
    'doctype'=>'TREATY',
    'header'=>Yii::t('misc','Attachment'),
    'ronly'=>($model->scenario=='view')
));
//$model->getInputBool()
?>

<?php $this->renderPartial('attr',array(
    'model'=>$model,
    'form'=>$form,
    'doctype'=>'TYINFO',
    'header'=>Yii::t('misc','Attachment'),
    'ronly'=>(false)
));
?>
<?php $this->renderPartial('//site/removedialog'); ?>

<?php
Script::genFileUpload($model,$form->id,'TREATY');
$js="
$('.td_end').click(function(e){
    if($(this).find('.fa').length>0){
        var id=$(this).data('id');
        var history_code=$(this).prevAll('.history_code').eq(0).text();
        var history_date=$(this).prevAll('.history_date').eq(0).text();
        $('#attrModel').find('.modal-title>small').remove();
        $('#attrModel').find('.modal-title').append('<small>（'+history_code+' _ '+history_date+'）</small>');
        
        $.ajax({
            type: 'get',
            url: '".Yii::app()->createUrl('treatyInfo/AjaxFileTable')."',
            data: {id:id},
            dataType: 'json',
            success: function(data){
                $('#tblFiletyinfo>tbody').html(data.html);
                $('#attrModel').modal('show');
            }
        });
    }
    e.stopPropagation();
});
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('treatyService/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

if ($model->scenario!='view') {
    $js = Script::genDatePicker(array(
        'TreatyServiceForm_company_date',
        'TreatyServiceForm_apply_date',
    ));
    Yii::app()->clientScript->registerScript('datePick',$js,CClientScript::POS_READY);
}
$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>


