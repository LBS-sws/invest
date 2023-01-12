<?php
$row = TreatyInfoForm::getTreatyRowForId($model->treaty_id);
?>

<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","company name"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-7">
        <?php echo Tbhtml::textField('company_name', $row["company_name"],
            array('readonly'=>(true))
        ); ?>
    </div>
</div>
<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","company date"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("company_date",$row["company_date"],
            array('readonly'=>(true),"autocomplete"=>"off",'prepend'=>'<span class="fa fa-calendar"></span>'));
        ?>
    </div>
    <?php echo Tbhtml::label(Yii::t("treaty","rate government"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("rate_government",$row["rate_government"],
            array('readonly'=>(true),'append'=>'%'));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","agent user"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("agent_user",$row["agent_user"],
            array('readonly'=>(true)));
        ?>
    </div>
    <?php echo Tbhtml::label(Yii::t("treaty","agent phone"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("agent_phone",$row["agent_phone"],
            array('readonly'=>(true)));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","annual money"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("annual_money",$row["annual_money"],
            array('readonly'=>(true),'append'=>Yii::t("treaty","W")));
        ?>
    </div>
    <?php echo Tbhtml::label(Yii::t("treaty","rate num"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("rate_num",$row["rate_num"],
            array('readonly'=>(true),'append'=>'%'));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","account type"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("account_type",TreatyServiceForm::getAccountType($row["account_type"],true),
            array('readonly'=>(true)));
        ?>
    </div>
    <?php echo Tbhtml::label(Yii::t("treaty","technician type"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-2">
        <?php
        echo Tbhtml::textField("technician_type",TreatyServiceForm::getTechnicianType($row["technician_type"],true),
            array('readonly'=>(true)));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo Tbhtml::label(Yii::t("treaty","sales source"),'',array('class'=>"col-lg-2 control-label")); ?>
    <div class="col-lg-7">
        <?php
        echo Tbhtml::textField("sales_source",$row["sales_source"],
            array('readonly'=>(true)));
        ?>
    </div>
</div>