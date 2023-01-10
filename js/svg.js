(function ($) {
    $.fn.svgSupport=function (options) {
        var defaults={
            //各种参数、各种属性
            "height":500,
            "dataList":[],
            "yList":[],
            "xList":["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],
        };
        var WIDTH = 0;
        var HEIGHT = 0;
        var XWIDTH = 0;//每個月份之間的間隔
        var XLIST = [];
        var YLIST = [];

        //options合并到defaults上,defaults继承了options上的各种属性和方法,将所有的赋值给endOptions
        var endOptions=$.extend(defaults,options);

        this.each(function () {
            $(this).html('');
            var width = $(this).parent('div').width();
            width = width<400?400:width;
            WIDTH = width-90;
            HEIGHT = endOptions["height"]-60;
            //实现功能的代码
            $(this).attr("width",width);
            $(this).attr("height",endOptions["height"]);
            setLink($(this),30,endOptions["height"]-30,width-45,endOptions["height"]-30,"stroke:rgb(0,0,0);stroke-width:2");//橫線
            setLink($(this),30,30,30,endOptions["height"]-30,"stroke:rgb(0,0,0);stroke-width:2");//豎線
            setText($(this),13,23,'font-size:13pt',"城市");
            setText($(this),width-40,endOptions["height"]-25,'font-size:13pt',"月份");

            setXYAll($(this));//繪製表格

            fillDataAll($(this));//填充數據
        });
        
        function fillDataAll(svg) {
            $.each(endOptions['dataList'],function (key, value) {
                var startYear = parseInt(value['start_date'],10);
                var day = parseInt(value['day'],10);
                var startMonth = value['start_date'].split("年");
                var startDay = value['start_date'].split("月");
                startMonth = parseInt(startMonth[1],10);
                startDay = parseInt(startDay[1],10);
                startDay = (startDay/day)*XWIDTH;
                var width = parseFloat(value['width'])*XWIDTH;
                var y = YLIST[value['city']];
                var xStart = XLIST[startMonth]+startDay;
                if(startYear !=$("#SupportEmployeeList_year").val()){
                    xStart =30;
                }
                width = (xStart+width)>WIDTH+45?(WIDTH+45-xStart):width;
                setRect(svg,xStart,y,width,HEIGHT-y+29,"fill:rgba(156,156,156,0.9);stroke-width:0;",value);
            });
        }

        function setXYAll(svg) {
            var xCount =endOptions['xList'].length;
            var yCount =endOptions['yList'].length+1;
            var xWidth = (WIDTH-12)/xCount;
            var yWidth = (HEIGHT-12)/yCount;
            XWIDTH = xWidth;
            XLIST[1] = 30;
            setText(svg,30,endOptions["height"]-13,'font-size:12pt',endOptions['xList'][0]);
            $.each(endOptions['xList'],function (key, value) {
                var x = key*xWidth+30;
                var name = parseInt(value,10);
                if(key!=0){
                    XLIST[name] = x;
                    setLink(svg,x,30,x,endOptions["height"]-30,"stroke:rgba(0,0,0,0.4);stroke-width:1;stroke-dasharray: 1");//豎線
                    setText(svg,x-15,endOptions["height"]-13,'font-size:12pt',value);
                }
            });
            $.each(endOptions['yList'],function (key, value) {
                var y = (key+1)*yWidth;
                YLIST[value] = y;
                setLink(svg,30,y,WIDTH+30,y,"stroke:rgba(0,0,0,0.4);stroke-width:1;stroke-dasharray: 1");//橫線
                setText(svg,0,y+7,'font-size:12pt',value);
            });
        }

        function setRect(svg,x,y,width,height,style,data) {
            var nameSpace = 'http://www.w3.org/2000/svg';
            var rect = document.createElementNS(nameSpace,'rect');//creat新的svg节点
            rect.style = style;
            rect.setAttribute('x',x);
            rect.setAttribute('y',y);
            rect.setAttribute('width',width);
            rect.setAttribute('height',height);
            $(rect).data(data);
            svg.append(rect);
        }

        function setLink(svg,x1,y1,x2,y2,style) {
            var nameSpace = 'http://www.w3.org/2000/svg';
            var line = document.createElementNS(nameSpace,'line');//creat新的svg节点
            line.style = style;
            line.setAttribute('x1',x1);
            line.setAttribute('y1',y1);
            line.setAttribute('x2',x2);
            line.setAttribute('y2',y2);
            svg.append(line);
        }

        function setText(svg,x,y,style,text) {
            var nameSpace = 'http://www.w3.org/2000/svg';
            var line = document.createElementNS(nameSpace,'text');//creat新的svg节点
            line.style = style;
            line.setAttribute('x',x);
            line.setAttribute('y',y);
            $(line).text(text);
            svg.append(line);
        }

/*        function setLink(svg,start,end,str) {
            var html = '<line x1="0" y1="0" x2="200" y2="200" style="stroke:rgba(0,0,0,0.4);stroke-width:1;stroke-dasharray: 1" />';
            svg.append(html);
        }*/

        function setPath(svg,start,end,str) {
            var html = '<path id="lineAB" d="M 100 350 l 150 -300" stroke="red" stroke-width="3" fill="none" />';
            svg.append(html);
        }
    };

    $("#svgSupport").delegate("rect",'click',function () {
        var data = $(this).data();
        var left = parseFloat($(this).attr("x"));
        var top = parseFloat($(this).attr("y"));
        var width = parseFloat($(this).attr("width"));
        var height = parseFloat($(this).attr("height"));
        var newLeft = left+(width/2-80);
        var newTop = top-140;
        var html = "<div class='supportDiv' id='supportDivTop'>";
        if(data['url']==""||data['url']==undefined){
            html+="<p>支点城市："+data['city_name']+"</p>";
        }else{
            html+="<p>支援编号：<a href='"+data['url']+"'>"+data['support_code']+"</a></p>";
            html+="<p>支援城市："+data['city_name']+"</p>";
        }
        html+="<p>开始日期："+data['start_date']+"</p>";
        html+="<p>結束日期："+data['end_date']+"</p>";
        html+="</div>";
        $("#supportDivTop").remove();
        $("#svgSupport").before(html);
        $("#supportDivTop").css({'left':newLeft,'top':newTop}).show();
    })
})(jQuery);