
//向表格內添加文檔
function addWord(data) {
    if($(this).prop("disabled")){
        return false;
    }
    var num = $("#wordArrTable>tbody>tr:last").attr("datanum");
    num = $("#wordArrTable>tbody>tr").length < 1?0:num;
    if(num == undefined && num == "" && num == undefined && isNaN(num)){
        alert("添加異常，請刷新頁面");
        return false;
    }
    num = parseInt(num)+1;
    var html ='<tr datanum="'+num+'">'+
            '<td><select class="form-control name" name="ContractForm[word_arr]['+num+'][name]"><option value=""></option>';
    var wordList = data.data.wordList;
    for(var id in wordList){
        html+='<option value="'+id+'">'+wordList[id]+'</option>';
    }

    html+='</select></td>'+
    '<td><input type="number" class="form-control type" name="ContractForm[word_arr]['+num+'][index]" ></td>'+
    '<td><button type="button" class="btn btn-danger delWord">'+data.data.btnStr+'</button></td>'+
    '</tr>';

    $("#wordArrTable>tbody").append(html);
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
                url: "./wordDelete",
                data: {id:dataId.val()},
                dataType: "json",
                success: function(data){
                    if(data.status == 1){
                        dataId.parents("tr").remove();
                    }else {
                        alert("Error:"+data.status);
                    }
                }
            });
        }
    }
}


