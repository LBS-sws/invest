
<script>
    function downloadFiletreaty(mid, did, fid) {
        href = '<?php echo Yii::app()->createUrl('treatyService/filedownload');?>?mastId='+mid+'&docId='+did+'&fileId='+fid+'&doctype=TREATY';
        window.open(href);
    }
</script>
<?php
	$doc = new DocMan($doctype,0,'TreatyServiceForm');

	$ftrbtn = array();
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>"attrModel",
					'header'=>$header,
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>
<div class="box" id="file-list" style="max-height: 300px; overflow-y: auto;">
	<table id="<?php echo $doc->tableName; ?>" class="table table-hover">
		<thead>
			<tr><th></th><th><?php echo Yii::t('dialog','File Name');?></th><th><?php echo Yii::t('dialog','Date');?></th></tr>
		</thead>
		<tbody>
<?php
if($model->scenario=='new'){
    if($model->docMasterId[strtolower($doc->docType)]>0){
        $doc->masterId = $model->docMasterId[strtolower($doc->docType)];
    }
}
if(get_class($model)=="HistoryForm"&&empty($model->id)){
    if($model->docMasterId[strtolower($doc->docType)]>0){
        $doc->masterId = $model->docMasterId[strtolower($doc->docType)];
    }
}
if(!isset($delBtn)){
    $delBtn = true;
}
echo $doc->genTableFileList($ronly,$delBtn);
?>
		</tbody>
	</table>
</div>
<div id="<?php echo $doc->listName; ?>" style="max-height: 100px; overflow-y: auto;">
</div>

<?php
	$this->endWidget(); 
?>
