<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('assess/index'));
}
$this->pageTitle=Yii::app()->name . ' - Assess Form';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'assess-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<style>
    *[readonly]{pointer-events: none;}
    .table_div{display: block;width: 100%;overflow-x: scroll;}
    .table-fixed{table-layout: fixed;margin: 0 auto;}
    .table-fixed th{white-space: normal;}
</style>
<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('fete','Evaluation Form'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('assess/index')));
		?>

        <?php if ($model->scenario=='edit'): ?>
            <?php
                echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                    'submit'=>Yii::app()->createUrl('assess/new'),
                ));
            ?>
            <?php echo TbHtml::button('<span class="fa fa-file"></span> '.Yii::t('misc','Copy'), array(
                'submit'=>Yii::app()->createUrl('assess/copy')));
            ?>
        <?php endif; ?>
        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-save"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('assess/save')));
            ?>
        <?php endif; ?>
        <?php if ($model->scenario=='edit'): ?>
            <?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
                    'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
            );
            ?>
        <?php endif; ?>
	</div>
            <div class="btn-group pull-right" role="group">
                <?php
                $counter = ($model->no_of_attm['assess'] > 0) ? ' <span id="docassess" class="label label-info">'.$model->no_of_attm['assess'].'</span>' : ' <span id="docassess"></span>';
                echo TbHtml::button('<span class="fa  fa-file-text-o"></span> '.Yii::t('misc','Attachment').$counter, array(
                        'name'=>'btnFile','id'=>'btnFile','data-toggle'=>'modal','data-target'=>'#fileuploadassess',)
                );
                ?>
            </div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model,'city',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'city',$model->getCityList(),
                        array('disabled'=>($model->scenario=='view'),'id'=>"city")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'employee_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">

                    <div class="input-group">
                        <?php echo $form->dropDownList($model, 'employee_id',$model->getEmployeeList($model->city),
                            array('disabled'=>($model->scenario=='view'),'id'=>"staff")
                        ); ?>
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="button" id="btn_history"><?php echo Yii::t("contract","assess history");?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'work_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'work_type',
                        array('readonly'=>(true),"id"=>"work_type")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'staff_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'staff_type',PrizeList::getPrizeList(),
                        array('disabled'=>($model->getInputBool()))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'overall_effect',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'overall_effect',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'service_effect',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'service_effect',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'service_process',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'service_process',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'carefully',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'carefully',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'judge',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'judge',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'deal',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'deal',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'connects',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'connects',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'obey',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'obey',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'leadership',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'leadership',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'characters',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'characters',
                        array('rows'=>3,'readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'assess',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'assess',
                        array('rows'=>3,'readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
		</div>
	</div>
</section>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="div_history" data-num="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo Yii::t("contract","assess history");?></h4>
            </div>
            <div class="modal-body"><!--  table-striped table-bordered table-hover-->
                <div class="table_div">
                    <table class="table table-striped table-bordered table-hover table-fixed">
                        <thead>
                        <tr>
                            <th width="110px"><?php echo Yii::t('fete','Evaluation Time')?></th>
                            <th width="90px"><?php echo Yii::t("fete","staff type");?></th>
                            <th width="90px"><?php echo Yii::t("fete","overall effect");?></th>
                            <th width="90px"><?php echo Yii::t("fete","service effect");?></th>
                            <th width="90px"><?php echo Yii::t("fete","service process");?></th>
                            <th width="90px"><?php echo Yii::t("fete","carefully");?></th>
                            <th width="90px"><?php echo Yii::t("fete","judge");?></th>
                            <th width="90px"><?php echo Yii::t("fete","deal");?></th>
                            <th width="90px"><?php echo Yii::t("fete","connect");?></th>
                            <th width="90px"><?php echo Yii::t("fete","obey");?></th>
                            <th width="90px"><?php echo Yii::t("fete","leadership");?></th>
                            <th width="200px"><?php echo Yii::t("fete","character");?></th>
                            <th width="500px"><?php echo Yii::t("fete","assess");?></th>
                            <th width="80px"><?php echo Yii::t("contract","Operation");?></th>
                        </tr>
                        </thead>
                        <tbody id="body_history">
                        <tr>
                            <td colspan="14">加载中....</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t("misc","Off");?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $this->renderPartial('//site/fileupload',array('model'=>$model,
    'form'=>$form,
    'doctype'=>'ASSESS',
    'header'=>Yii::t('misc','Attachment'),
    'ronly'=>($model->getInputBool()),
));
//$model->getInputBool()
?>
<?php
$this->renderPartial('//site/removedialog');
?>
<script type="text/javascript">
    $(function(){
        $('#btn_history').click(function(){
            $('#div_history').modal('show');
            var staff_id = $('#staff').val();
            var num = $('#div_history').data('num');
            if(staff_id != num){
                $('#body_history').html("<tr><td colspan='14'>加载中....</td></tr>");
                $.ajax({
                    type: 'post',
                    url: "<?php echo Yii::app()->createUrl('assess/ajaxHistory');?>",
                    data: { "staff_id":staff_id,"id":"<?php echo $model->id;?>"},
                    dataType: 'json',
                    success: function(data){
                        $('#body_history').html(data.html);
                        if(data.status == 1){
                            $('#div_history').data('num',staff_id);
                        }
                    }
                });
            }
        });

        $('#body_history').delegate(".insert_history","click",function () {
            var list =["service_effect","service_process","carefully","overall_effect","judge","deal","connects","obey","leadership","characters","assess"];
            var jQueryTr = $(this).parents("tr:first");
            $.each(list,function (key,val) {
                var text = jQueryTr.children("td[data-str='"+val+"']").text();
                $("input[name$='["+val+"]'],textarea[name$='["+val+"]']").val(text);
            });
        });

    })
</script>
<?php
Script::genFileUpload($model,$form->id,'ASSESS');

$js = "
    $('#city').on('change',function(){
        if($(this).val() != ''){
            $.ajax({
                type: 'post',
                url: '".Yii::app()->createUrl('assess/ajaxCity')."',
                data: {city:$(this).val()},
                dataType: 'json',
                success: function(data){
                    if(data.status == 1){
                        var staffList = data.staffList;
                        $('#work_type').val('');
                        $('#staff').html('<option></option>');
                        $.each(staffList,function(i,n){
                            $('#staff').append('<option value=\"'+i+'\">'+n+'</option');
                        });
                    }
                }
            });
        }
    });
    
    $('#staff').on('change',function(){
        if($(this).val() != ''){
            $.ajax({
                type: 'post',
                url: '".Yii::app()->createUrl('assess/ajaxStaff')."',
                data: {staff:$(this).val()},
                dataType: 'json',
                success: function(data){
                    if(data.status == 1){
                        var staffList = data.staffList;
                        $('#city').val(staffList.city);
                        $('#work_type').val(staffList.work_type);
                    }
                }
            });
        }
    });
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('assess/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

