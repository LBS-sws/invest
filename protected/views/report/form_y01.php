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
		<strong><?php echo Yii::t('monthly','Sales Summary'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('report/salessummary'))); 
		?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'id'); ?>
			<?php echo $form->hiddenField($model, 'name'); ?>
			<?php echo $form->hiddenField($model, 'fields'); ?>

			<div class="form-group">
				<?php echo $form->labelEx($model,'year',array('class'=>"col-sm-2 control-label")); ?>
				<div class="col-sm-3">
					<?php 
						$item = array();
						for ($i=2017;$i<=2027;$i++) {$item[$i] = $i; }
						echo $form->dropDownList($model, 'year', $item); 
					?>
				</div>
			</div>
		
			<div class="form-group">
				<?php echo $form->labelEx($model,'month',array('class'=>"col-sm-2 control-label")); ?>
				<div class="col-sm-3">
					<?php 
						$item = array();
						for ($i=1;$i<=12;$i++) {$item[$i] = $i; }
						echo $form->dropDownList($model, 'month', $item); 
					?>
				</div>
			</div>
<?php if (Yii::app()->user->validFunction('YN01')) :?>
			<div class="form-group">
				<?php echo $form->labelEx($model,'region',array('class'=>"col-sm-2 control-label")); ?>
				<div class="col-sm-3">
					<?php 
						$item = array(
								1=>Yii::t('report','China & Franchise'),
//								2=>Yii::t('report','South East Asia'),
							);
						echo $form->dropDownList($model, 'region', $item); 
					?>
				</div>
			</div>
<?php else : ?>
			<?php echo $form->hiddenField($model, 'region'); ?>
<?php endif ?>

		</div>
	</div>
</section>

<?php $this->endWidget(); ?>

</div><!-- form -->

