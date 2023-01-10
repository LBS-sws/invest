<?php
$this->pageTitle=Yii::app()->name . ' - Assess';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'assess-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Staff appraisal'); ?></strong>
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
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('ZE07'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                        'submit'=>Yii::app()->createUrl('assess/new'),
                    ));
                ?>
            </div>
            <div class="btn-group pull-right" role="group">
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('ZE07'))
                    echo TbHtml::button('<span class="fa fa-envelope-o"></span> '.Yii::t('fete','sent email'), array(
                        'id'=>'btnSent','data-toggle'=>'modal','data-target'=>'#assess_email'
                    ));
                ?>
            </div>
        </div></div>
    <?php
    $search = array(
        'employee_code',
        'employee_name',
        'lcu',
        'staff_type',
    );
    $search_add_html="";
    $modelName = get_class($model);
    $search[] = 'city_name';
    $search_add_html .= TbHtml::textField($modelName.'[searchTimeStart]',$model->searchTimeStart,
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));
    $search_add_html.="<span>&nbsp;&nbsp;-&nbsp;&nbsp;</span>";
    $search_add_html .= TbHtml::textField($modelName.'[searchTimeEnd]',$model->searchTimeEnd,
        array('size'=>15,'placeholder'=>Yii::t('misc','End Date'),"class"=>"form-control","id"=>"end_time"));

    $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('fete','Evaluation list'),
        'model'=>$model,
        'viewhdr'=>'//assess/_listhdr',
        'viewdtl'=>'//assess/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
        'search_add_html'=>$search_add_html,
        'search'=>$search,
    ));
    ?>
</section>
<?php
echo $form->hiddenField($model,'pageNum');
echo $form->hiddenField($model,'totalRow');
echo $form->hiddenField($model,'orderField');
echo $form->hiddenField($model,'orderType');
?>

<div id="assess_email" role="dialog" tabindex="-1" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 class="modal-title"><?php echo Yii::t('fete','sent email');?></h4></div><div class="modal-body"><p></p>
                <div class="media">
                    <div class="media-left">
                        <?php echo $form->labelEx($model,'email_list',array('class'=>"control-label","style"=>"width:60px")); ?>
                    </div>
                    <div class="media-body">
                        <?php echo $form->textArea($model, 'email_list',
                            array('rows'=>4,'readonly'=>($model->scenario=='view'),"style"=>"width:100%","id"=>"email_list")
                        ); ?>
                    </div>
                    <div class="media-right media-bottom">
                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#email_check">选择</button>
                    </div>
                </div>
                <P>&nbsp;</P>
                <p class="text-danger">多个邮件请用;号分割。例如：aaa@lbsgroup.com.cn;bbb@lbsgroup.com.cn</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="send_email" type="button">发送</button>
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
            </div>
        </div>
    </div>
</div>

<div id="email_check" role="dialog" tabindex="-1" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 class="modal-title"><?php echo Yii::t('contract','Email');?></h4></div>
            <div class="modal-body">
                <div>
                    <input id="emailNameSearch" type="text" class="form-control" placeholder="用戶名稱">
                </div>
                <p></p>
                <div>

                    <?php echo $form->checkBoxList($model, 'test',$model->getEmailList(),array("class"=>"check_dev")); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="dev_ok">插入</button>
                <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$emailJSON = $model->getEmailList();
$emailJSON = empty($emailJSON)?"''":json_encode($emailJSON);
$js = "
EMAIL_JSON =$emailJSON;
EMAIL_LIST=';';//郵箱列表
$('#emailNameSearch').keyup(function(){
    var search = $(this).val();
    if(EMAIL_JSON == ''){
        return false;
    }
    $('#AssessList_test').html('');
    $.each(EMAIL_JSON,function(key,value){
        var email = key;
        email = email.split('!');
        email = ';'+email[1]+';';
        if(value.indexOf(search)>=0||search == ''){
            if(EMAIL_LIST.indexOf(email)>=0){
                $('#AssessList_test').append('<div class=\'checkbox\'><label><input checked class=\'check_dev\' value=\''+key+'\' type=\'checkbox\' name=\'AssessList[test][]\'>'+value+'</label></div>');
            }else{
                $('#AssessList_test').append('<div class=\'checkbox\'><label><input class=\'check_dev\' value=\''+key+'\' type=\'checkbox\' name=\'AssessList[test][]\'>'+value+'</label></div>');
            }
        }
    });
});
$('#start_time').datepicker({autoclose: true, format: 'yyyy/mm/dd',language: 'zh_cn'});
$('#end_time').datepicker({autoclose: true, format: 'yyyy/mm/dd',language: 'zh_cn'});
$('.checkBoxSent').on('click',function(e){
    e.stopPropagation();
});
$('#dev_ok').on('click',function(){
    if(EMAIL_LIST==';'){
        $('#email_list').val('');
    }else{
        $('#email_list').val(EMAIL_LIST.slice(1));
    }
    $('#email_check').modal('hide');
});
$('#email_check').delegate('.check_dev','click',function(){
    var email=$(this).val();
    email = email.split('!');
    email = email[1]+';';
    EMAIL_LIST=EMAIL_LIST.split(';'+email).join(';');
    if($(this).is(':checked')){
        EMAIL_LIST+=email;
    }
});
$('#send_email').on('click',function() {
	var elm=$('#send_email');
	jQuery.yii.submitForm(elm,'".Yii::app()->createUrl('assess/sent')."',{});
});
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

