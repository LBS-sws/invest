<?php
$this->pageTitle=Yii::app()->name . ' - Report';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'report-form',
    'action'=>Yii::app()->createUrl('report/generate'),
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('report',$model->name); ?></strong>
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
                <?php echo TbHtml::button(Yii::t('misc','Submit'), array(
                    'submit'=>Yii::app()->createUrl('report/pennantculist')));
                ?>
            </div>
        </div></div>

    <div class="box box-info">
        <div class="box-body">
            <?php echo $form->hiddenField($model, 'id'); ?>
            <?php echo $form->hiddenField($model, 'name'); ?>
            <?php echo $form->hiddenField($model, 'fields'); ?>
            <?php echo $form->hiddenField($model, 'staffs'); ?>

            <?php if ($model->showField('city') && !Yii::app()->user->isSingleCity()): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'city',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'city', General::getCityListWithNoDescendant(Yii::app()->user->city_allow()),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php else: ?>
                <?php echo $form->hiddenField($model, 'city'); ?>
            <?php endif ?>

            <div class="form-group">
                <?php echo $form->labelEx($model,'year',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'year',ReportY05Form::getYearList(),
                        array('disabled'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'month',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'month',ReportY05Form::getMonthList(),
                        array('disabled'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'staffs',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-6">
                    <?php
                    echo $form->textArea($model, 'staffs_desc',
                        array('rows'=>4,'cols'=>80,'maxlength'=>1000,'readonly'=>true,)
                    );
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    echo TbHtml::button('<span class="fa fa-search"></span> '.Yii::t('report','Staffs'),
                        array('name'=>'btnStaff','id'=>'btnStaff',)
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->renderPartial('//site/lookup'); ?>

<?php
$js = Script::genLookupSearchEx();
Yii::app()->clientScript->registerScript('lookupSearch',$js,CClientScript::POS_READY);

$js = Script::genLookupButtonEx('btnStaff', 'staff', 'staffs', 'staffs_desc',
    array(),
    true
);
Yii::app()->clientScript->registerScript('lookupStaffs',$js,CClientScript::POS_READY);

$js = Script::genLookupSelect();
Yii::app()->clientScript->registerScript('lookupSelect',$js,CClientScript::POS_READY);

?>

<?php $this->endWidget(); ?>

</div><!-- form -->

