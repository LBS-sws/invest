<?php
echo "<form method='post'>";
	$ftrbtn = array();
	$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnWFClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_DEFAULT,"class"=>"pull-left"));
	$ftrbtn[] = TbHtml::button(Yii::t('misc','Download'), array('id'=>'btnWFSubmit','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY,'submit' => $submit));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'jectdialog',
					'header'=>Yii::t("contract","Word List"),
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="form-group">
    <input type="hidden" name="id" value="<?php echo $model->id?>">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th class="text-center"><input id="checkAll" type="checkbox"></th>
            <th><?php echo Yii::t("contract","Word Name"); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $wordList = ContractForm::getWordListToConIdDesc($model->contract_id);
        if(!empty($wordList)){
            $wordForm = new WordForm();
            foreach ($wordList as $word){
                $wordForm->retrieveData($word["name"]);
                echo "<tr>";
                echo "<td class='text-center'><input type='checkbox' name='word[]' value='".$wordForm->docx_url."'></td>";
                echo "<td>".$wordForm->name."</td>";
                echo "</tr>";
            }
        }

        ?>
        </tbody>
    </table>
</div>

<script>
    $("#checkAll").on("change",function () {
        if($(this).is(":checked")){
            $(this).parents("table:first").find("input[type='checkbox']").prop("checked",true);
        }else{
            $(this).parents("table:first").find("input[type='checkbox']").prop("checked",false);
        }
    })
</script>
<?php
$this->endWidget();
echo "</form>";
?>
