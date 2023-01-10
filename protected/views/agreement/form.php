<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('agreement/index'));
}
$this->pageTitle=Yii::app()->name . ' - Agreement Form';
?>

<style>
    td,td>p{word-break:break-all;word-wrap:break-word;}
</style>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'agreement-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('contract','Agreement Form'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('agreement/index')));
		?>

        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('agreement/save')));
            ?>
            <?php if ($model->scenario=='edit'): ?>
                <?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
                        'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
                );
                ?>
            <?php endif; ?>
        <?php endif ?>
	</div>

	<div class="btn-group pull-right" role="group">
        <?php if ($model->scenario=='edit'): ?>
            <?php echo TbHtml::button('<span class="fa fa-cloud-download"></span> '.Yii::t('contract','Down'), array(
                'submit'=>Yii::app()->createUrl('agreement/downfile?index='.$model->id)));
            ?>
        <?php endif; ?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>
			<?php echo $form->hiddenField($model, 'city'); ?>
			<?php echo $form->hiddenField($model, 'type'); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model,'city',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'city',CompanyList::getSingleCityToList(),
                        array('disabled'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'name',array('class'=>"col-sm-2 control-label")); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'name',
						array('size'=>50,'maxlength'=>50,'readonly'=>($model->scenario=='view'))
					); ?>
				</div>
			</div>


            <div class="form-group">
                <?php echo $form->labelEx($model,'file',array('class'=>"col-sm-2 control-label")); ?>
                <?php if ($model->scenario=='new'): ?>
                <div class="col-sm-3 word">
                    <?php echo $form->fileField($model, 'file',
                        array('disabled'=>($model->scenario=='view'),'class'=>'form-control')
                    ); ?>
                </div>
                 <?php else:?>
                <div class="col-sm-3 word">
                    <?php echo $form->hiddenField($model, 'docx_url'); ?>
                    <?php echo TbHtml::button('<span class="fa fa-eye"></span> '.Yii::t('contract','Preview'),
                        array('name'=>'btnFlow','id'=>'btnFlow','data-toggle'=>'modal','data-target'=>'#flowinfodialog'));
                    ?>

                    <?php if ($model->scenario!='view'): ?>
                        <?php echo TbHtml::button('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('contract','update'), array("id"=>"updateWord"));
                        ?>
                    <?php endif ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->inlineRadioButtonList($model, 'type',array(Yii::t("contract","local"),Yii::t("contract","default")),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
		</div>
	</div>
</section>

<?php
if ($model->scenario!='new')
    $this->renderPartial('//site/flowword',array('model'=>$model));

$this->renderPartial('//site/removedialog');
?>
<?php
$wordFileInput="<div class='col-sm-3 word'><input id='ytWordForm_file' type='hidden' value='' name='AgreementForm[file]'><input class='form-control' name='AgreementForm[file]' id='WordForm_file' type='file'></div>";
$js = '
    $("#updateWord").on("click",function(){
        var $div = $(this).parents("div.word");
        $div.after("'.$wordFileInput.'").remove();
    });
    function reTableToScript() {
        $("table").each(function () {
            var $table = $(this);
            var count = $(this).find("tr").length;
            $(this).find("td[restart=\'restart\']").each(function () {
                var col = $(this).prevAll("td").length;
                var row = $(this).parent("tr").prevAll("tr").length;
                var num = 0;
                for(var i = row;i<count-1;i++){
                    var td = $table.find("tr").eq(i).find("td").eq(col);
                    if(td.attr("restart")){
                        num++;
                        $(this).prop("rowspan",num);
                        if(td.attr("restart") == "ok"){
                            td.remove();
                        }
                    }
                }
            })
        })
    }
    reTableToScript();
';
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('agreement/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

