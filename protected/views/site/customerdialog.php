<?php
$ftrbtn = array();
$ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>'btnCuClose','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_DEFAULT,"class"=>"pull-left"));
$ftrbtn[] = TbHtml::button(Yii::t('dialog','OK'), array('id'=>'btnCuSubmit','data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
$this->beginWidget('bootstrap.widgets.TbModal', array(
    'id'=>'customerdialog',
    'header'=>Yii::t('fete','customer name'),
    'footer'=>$ftrbtn,
    'show'=>false,
    'htmlOptions'=>array("class"=>"form-inline")
));
?>
<div style="margin-bottom: 5px;">
    <div class="form-group">
        <label><?php echo Yii::t('fete','customer code'); ?></label>
        <input type="text" class="form-control" id="search_code">
    </div>
    <div class="form-group">
        <label><?php echo Yii::t('fete','customer name'); ?></label>
        <input type="text" class="form-control" id="search_name">
    </div>
    <button type="button" class="btn btn-default" id="search_cu_btn"><?php echo Yii::t('misc','Search'); ?></button>
</div>
<div class="box" style="max-height: 300px; overflow-y: auto;margin-bottom: 5px;">
    <table id="customer_tb" class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th width="5%"></th>
            <th width="17%"><?php echo Yii::t('fete','customer code'); ?></th>
            <th><?php echo Yii::t('fete','customer name'); ?></th>
            <th width="18%"><?php echo Yii::t('fete','contact'); ?></th>
            <th width="18%"><?php echo Yii::t('fete','contact phone'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $customerList = new CustomerList;
        $customerList->noOfItem = 5;
        $customerList->determinePageNum(0);
        $customerList->retrieveDataByPage($customerList->pageNum);
        if(!empty($customerList->attr)){
            foreach ($customerList->attr as $list){
                echo "<tr>";
                echo "<td><input type='radio' name='customer_radio' value='".$list['id']."'></td>";
                echo "<td class='cur_code'>".$list['code']."</td>";
                echo "<td class='cur_name'>".$list['name']."</td>";
                echo "<td class='cur_user_name'>".$list['cont_name']."</td>";
                echo "<td class='cur_user_phone'>".$list['cont_phone']."</td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
</div>

<div id="customer_footer">
    <?php
    echo TbHtml::pagination($customerList->getPageList(), array('class'=>'pagination pagination-sm no-margin'));
    ?>
    <?php echo "<div class='pull-right'>".Yii::t('misc','Record')."：<span>".$customerList->totalRow."</span></div>";?>
</div>
<?php
$this->endWidget();
?>
<script>
    $(function ($) {
        //".Yii::app()->createUrl('prize/ajaxCustomer')."
        $("#search_cu_btn").on("click",function () {
            resetTable(0);
        });
        $("#btnCuSubmit").on("click",function () {
            var $radioInput= $('input:radio[name="customer_radio"]:checked');
            if($radioInput.val() != null){
                var $tr = $radioInput.parents("tr:first");
                $("#customer_name").val($radioInput.val());
                $("#customer_dis").val($tr.find(".cur_name:first").text());
                $("#cont_name").val($tr.find(".cur_user_name:first").text());
                $("#cont_phone").val($tr.find(".cur_user_phone:first").text());
            }
        });
    });

    function resetTable(pageNum) {
        $.ajax({
            type: 'post',
            url: '<?php echo Yii::app()->createUrl('prize/ajaxCustomer');?>',
            data: {
                search_code:$("#search_code").val(),
                search_name:$("#search_name").val(),
                pageNum:pageNum,
            },
            dataType: 'json',
            success: function(data){
                if(data.status == 1){
                    var customerList = data.attr;
                    var $tBody = $("#customer_tb>tbody:first");
                    $tBody.html("");
                    $.each(customerList, function(i, item){
                        var html = "<tr>";
                        html+="<td><input type='radio' name='customer_radio' value='"+item['id']+"'></td>";
                        html+="<td class='cur_code'>"+item['code']+"</td>";
                        html+="<td class='cur_name'>"+item['name']+"</td>";
                        html+="<td class='cur_user_name'>"+item['cont_name']+"</td>";
                        html+="<td class='cur_user_phone'>"+item['cont_phone']+"</td>";
                        html+="</tr>";
                        $tBody.append(html);
                    });
                    $("#customer_footer").html(data.pageHtml);
                }
            }
        });
    }
</script>
