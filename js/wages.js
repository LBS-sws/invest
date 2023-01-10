function addWagesType(data) {

    if($(this).prop("disabled")){
        return false;
    }
    var num = $("#wagesTable>tbody>tr:last").data("num");
    num = $("#wagesTable>tbody>tr").length < 1?0:num;
    if(num == undefined && num == "" && num == undefined && isNaN(num)){
        alert("添加異常，請刷新頁面");
        return false;
    }
    num = parseInt(num)+1;
/*
    var computeList = data.data.list;
    var select = "<select class='form-control' name='WagesForm[wages_list]["+num+"][compute]'>";
    for (var i= 0;i<computeList.length;i++){
        select+="<option value='"+i+"'>"+computeList[i]+"</option>";
    }
    select+="</select>";*/

    var html ='<tr data-num="'+num+'">'+
        '<td><input type="text" class="form-control" name="WagesForm[wages_list]['+num+'][type_name]" ></td>'+
        '<td><input type="number" class="form-control" name="WagesForm[wages_list]['+num+'][z_index]" ></td>'+
        '<td><button type="button" class="btn btn-danger delWages">'+data.data.btnStr+'</button></td>'+
        '</tr>';

    $("#wagesTable>tbody").append(html);
}


//刪除表格里的某條物品
function delWordTable(data) {
    if($(this).prop("disabled")){
        return false;
    }
    var dataId = $(this).next("input");
    if(dataId.length < 1){
        $(this).parents("tr").remove();
    }else{
        if(confirm(data.data)){
            $.ajax({
                type: "post",
                url: "./wagesTypeDelete",
                data: {id:dataId.val()},
                dataType: "json",
                success: function(data){
                    if(data.status == 1){
                        dataId.parents("tr").remove();
                    }else {
                        window.location.reload();
                    }
                }
            });
        }
    }
}

//員工的工資單變化
function ajaxWagesChange(par) {
    var dataObj = par.data;
    var form = dataObj["form"];
    var $parentDiv = $("#"+form+"_price1").parents(".form-group:first");
    $parentDiv.next(".changeWages").remove();
    var listDefine = "";
    var bool = $("#"+form+"_price1").prop("disabled");
    if(typeof(DEFINE_WAGES)!=="undefined"){
        listDefine = DEFINE_WAGES;
        DEFINE_WAGES = "";
    }
    if($("#"+form+"_price1").val() == ""){
        return false;
    }
    $.ajax({
        type: "post",
        url: dataObj['url'],
        data: {id:$("#"+form+"_price1").val()},
        dataType: "json",
        success: function(data){
            if(data.status == 1){
                var wagesList = data.data;
                var tdInput = "";
                var value = "";
                var html ='<div class="form-group changeWages">';
                html+='<label class="col-sm-2 control-label">'+dataObj['str']+'</label>';
                html+='<div class="col-sm-8"><table class="table table-bordered table-striped"><thead><tr>';
                for(var i = 0;i<wagesList.length;i++){
                    html+="<th width='10%'>"+wagesList[i]["type_name"]+"</th>";
                    value = listDefine[i];
                    if(value == undefined || value == null){
                        value = "";
                    }
                    tdInput+="<td><input class='form-control' type='number' name='"+form+"[price3][]' value='"+value+"'";
                    if(bool){
                        tdInput+=" disabled";
                    }
                    tdInput+=" ></td>";
                }

                html+='</tr></thead><tbody><tr>'+tdInput+'</tr></tbody></table></div></div>';
                $parentDiv.after(html);
            }else {
                alert(data.error);
            }
        }
    });
}

/*根据出生日期算出年龄*/
function jsGetAge(strBirthday){
    var returnAge;
    var strBirthdayArr=strBirthday.split("/");
    var birthYear = parseInt(strBirthdayArr[0],10);
    var birthMonth = parseInt(strBirthdayArr[1],10);
    var birthDay = parseInt(strBirthdayArr[2],10);

    var d = new Date();
    var nowYear = d.getFullYear();
    var nowMonth = d.getMonth() + 1;
    var nowDay = d.getDate();

    if(nowYear == birthYear){
        returnAge = 0;//同年 则为0岁
    }
    else{
        var ageDiff = nowYear - birthYear ; //年之差
        if(ageDiff > 0){
            if(nowMonth == birthMonth) {
                var dayDiff = nowDay - birthDay;//日之差
                if(dayDiff < 0)
                {
                    returnAge = ageDiff - 1;
                }
                else
                {
                    returnAge = ageDiff ;
                }
            }
            else
            {
                var monthDiff = nowMonth - birthMonth;//月之差
                if(monthDiff < 0)
                {
                    returnAge = ageDiff - 1;
                }
                else
                {
                    returnAge = ageDiff ;
                }
            }
        }
        else
        {
            returnAge = -1;//返回-1 表示出生日期输入错误 晚于今天
        }
    }
    return returnAge;//返回周岁年龄
}