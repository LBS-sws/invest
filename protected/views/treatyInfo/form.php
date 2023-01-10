<?php
$this->pageTitle=Yii::app()->name . ' - TreatyInfo Form';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'TreatyInfo-form',
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
				'submit'=>Yii::app()->createUrl('treatyService/edit',array("index"=>$model->treaty_id))));
		?>
<?php if ($model->scenario!='view'): ?>
			<?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
				'submit'=>Yii::app()->createUrl('treatyInfo/save')));
			?>
<?php endif ?>
<?php if ($model->scenario!='new' && $model->scenario!='view'): ?>
	<?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
			'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
		);
	?>
<?php endif ?>
	</div>
            <div class="btn-group pull-right" role="group">
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
			<?php echo $form->hiddenField($model, 'treaty_id'); ?>

            <?php $this->renderPartial('companyForm',array("model"=>$model)); ?>


            <?php if ($model->scenario!='new'): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'history_code',array('class'=>"col-lg-2 control-label")); ?>
                    <div class="col-lg-2">
                        <?php echo $form->textField($model, 'history_code',
                            array('readonly'=>(true))
                        ); ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'info_state',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->dropDownList($model, 'info_state',TreatyInfoForm::getInfoStateList(),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'history_date',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-2">
                    <?php echo $form->textField($model, 'history_date',
                        array('readonly'=>($model->scenario=='view'),"autocomplete"=>"off",'prepend'=>'<span class="fa fa-calendar"></span>')
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'history_matter',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-4">
                    <?php echo $form->textArea($model, 'history_matter',
                        array('readonly'=>($model->scenario=='view'),'rows'=>4)
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'remark',array('class'=>"col-lg-2 control-label")); ?>
                <div class="col-lg-4">
                    <?php echo $form->textArea($model, 'remark',
                        array('readonly'=>($model->scenario=='view'),'rows'=>4)
                    ); ?>
                </div>
            </div>

		</div>
	</div>
</section>

<?php
$id = $model->id;
$model->id = $model->treaty_id;
$this->renderPartial('//site/fileupload',array(
    'model'=>$model,
    'form'=>$form,
    'doctype'=>'TREATY',
    'header'=>Yii::t('misc','Attachment'),
    'ronly'=>($model->scenario=='view')
));
$model->id = $id;
?>
<?php $this->renderPartial('//site/removedialog'); ?>

<?php
Script::genFileUpload($model,$form->id,'TREATY');
//.trigger('change')
$js ="
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
if ($model->scenario!='view') {
    $js = Script::genDatePicker(array(
        'TreatyInfoForm_history_date',
    ));
    Yii::app()->clientScript->registerScript('datePick',$js,CClientScript::POS_READY);
}
$js = Script::genDeleteData(Yii::app()->createUrl('treatyInfo/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>


