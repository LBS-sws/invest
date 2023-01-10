<?php

class MyPDFTwo {
	protected $_PDF;

	public function __construct() {
        $phpExcelPath = Yii::getPathOfAlias('ext.TCPDF2');
        spl_autoload_unregister(array('YiiBase','autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'tcpdf.php');
		$this->_PDF = new tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // 是否显示页眉
        $this->_PDF->setPrintHeader(false);
        // 是否显示页脚
        $this->_PDF->setPrintFooter(false);
        // 设置是否自动分页  距离底部多少距离时分页
        $this->_PDF->SetAutoPageBreak(TRUE, '5');
        // 设置默认等宽字体
        $this->_PDF->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // 设置行高
        $this->_PDF->setCellHeightRatio(1);
        // 设置左、上、右的间距
        $this->_PDF->SetMargins('10', '10', '10');
        // 设置图像比例因子
        $this->_PDF->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->_PDF->setFontSubsetting(true);
	}

    public function setPageToLeave($arr=array()){
	    if(Yii::app()->language == "en"){
            $this->setPageToLeaveToEnglish($arr);
        }else{
            $this->setPageToLeaveToChina($arr);
        }
    }
	//210mm×297mm
	//190mm×287mm
	public function setPageToLeaveToChina($arr=array()){
        $suffix = Yii::app()->basePath;
        $this->_PDF->AddPage();
        //员工请假单
        $this->_PDF->Image("$suffix/../images/LBS_Group.jpg",92,5,25,22);
        $this->_PDF->SetFont('stsongstdlight', '', 18, '', true);
        $html = "员工请假单";
        $this->_PDF->writeHTMLCell(190, 10, 10,33, $html, 0, 1, 0, true, 'C', true);


        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        //繪製表格
        $this->_PDF->MultiCell(190,12,"",1);
        $this->_PDF->MultiCell(190,12,"",1);
        $this->_PDF->MultiCell(190,40,"",1);
        $this->_PDF->MultiCell(190,20,"",1);
        $this->_PDF->MultiCell(190,25,"",1);
        //竖线
        $this->_PDF->Line(35,43,35,79);
        $this->_PDF->Line(65,43,65,67);
        $this->_PDF->Line(90,43,90,67);
        $this->_PDF->Line(130,43,130,55);
        $this->_PDF->Line(160,43,160,55);

        $this->_PDF->Line(30,107,30,152);
        $this->_PDF->Line(45,107,45,152);
        $this->_PDF->Line(85,107,85,152);
        $this->_PDF->Line(125,107,125,152);
        $this->_PDF->Line(155,107,155,152);

        //$this->_PDF->Line(115,162,200,162);
        $this->_PDF->Line(10,79,200,79);
        $this->_PDF->Line(54,171,98,171);
        $this->_PDF->Line(145,171,200,171);

        $this->_PDF->MultiCell(190,5,"",0,"L");
        $html = "<p><b>本人在此向公司申请休假（具体情况见上），请公司予以批准。</b></p>";
        $this->_PDF->writeHTMLCell(190,8,"","",$html);

        $this->_PDF->MultiCell(100,8,"",0);
        $this->_PDF->MultiCell(100,8,"员工签名：",0,"L",false,0,30);
        $this->_PDF->MultiCell(100,8,"日期：",0,"L",false,0);
        //$this->_PDF->writeHTMLCell(190, 8, 20,"", $html, 0, 1, 0, true, 'L', true);
        //繪製表格
        $this->_PDF->SetFillColor(10,10,10,10);
        $this->_PDF->writeHTMLCell(190, 8, 10,"", "", 0, 1, 0, true, 'L', true);
        $this->_PDF->MultiCell(190,10,"",1,"L",true);
        $this->_PDF->MultiCell(190,20,"",1,"L");
        $this->_PDF->MultiCell(190,10,"",1,"L",true);
        $this->_PDF->MultiCell(190,30,"",1,"L");
        $this->_PDF->MultiCell(190,2,"",0,"L");
        //竖线
        $this->_PDF->Line(52,183,52,203);
        $this->_PDF->Line(52,213,52,243);
        $this->_PDF->Line(100,183,100,203);
        $this->_PDF->Line(100,213,100,243);
        $this->_PDF->Line(118,213,118,243);
        $this->_PDF->Line(148,213,148,243);
        $this->_PDF->Line(172,213,172,243);

        $this->_PDF->Line(52,193,200,193);
        $this->_PDF->Line(10,228,100,228);

        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $html = "<p>备注：<br>";
        $html .= "1.请假单必须填写完整、无遗漏。<br>";
        $html .= "2.一次性申请年假天数为3天（含3天）以上的，必须提前2周提出休假申请。<br>";
        $html .= "3.病假需及时提供三级甲等医院出具的挂号单据、病历和病假单作为证明，前述材料如不齐全，按事假处理。  <br>";
        $html .= "4.请假流程：员工在LBS系统在线填写此请假单，由有审批权限的相关负责人进行审批（不足3天的请假由部门主管进行审批，3天及以上的请假由公司法定代表人/负责人审批；部门主管的所有请假均由公司法定代表人/负责人审批），审批完成后，再由公司人事专员下载打印该请假单，并让员工签字进行确认，签字后的请假单再上传至员工请假单附件处，请假单原件由公司存档。<br>";
        $html .= "5. 该请假单适用于公司内的所有员工。公司法定代表人/负责人的请假，由相应的史伟莎集团区域经理/总监和中国区营运总监进行审批。</p>";
        $this->_PDF->writeHTMLCell(190, 5, 10,245, $html, 0, 1, 0, true, 'L', true);

        $html = "姓名";
        $this->_PDF->writeHTMLCell(25, 8, 10,47, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_name"];
        $this->_PDF->writeHTMLCell(30, 8, 35,47, $html, 0, 1, 0, true, 'C', true);
        $html = "员工工号";
        $this->_PDF->writeHTMLCell(25, 8, 65,47, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_code"];
        $this->_PDF->writeHTMLCell(40, 8, 90,47, $html, 0, 1, 0, true, 'C', true);
        $html = "入职日期";
        $this->_PDF->writeHTMLCell(30, 8, 130,47, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["entry_time"];
        $this->_PDF->writeHTMLCell(40, 8, 160,47, $html, 0, 1, 0, true, 'C', true);
        $html = "部门";
        $this->_PDF->writeHTMLCell(25, 8, 10,59, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["dept_name"];
        $this->_PDF->writeHTMLCell(30, 8, 35,59, $html, 0, 1, 0, true, 'C', true);
        $html = "岗位";
        $this->_PDF->writeHTMLCell(25, 8, 65,59, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["posi_name"];
        $this->_PDF->writeHTMLCell(110, 8, 90,59, $html, 0, 1, 0, true, 'C', true);
        $html = "公司";
        $this->_PDF->writeHTMLCell(25, 8, 10,71, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["company_name"];
        $this->_PDF->writeHTMLCell(160, 8, 38,71, $html, 0, 1, 0, true, 'L', true);
        $signature = LeaveForm::getSignatureToStaffId($arr["employee_id"]);
        if($signature){
            $this->setSignature($signature, 60, 153, 0,16);
        }

        $html = "<p><b>休假类别：</b></p>";
        $this->_PDF->writeHTMLCell(190, 9, 11,82, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>A类：加班调休、年休假、特别调休</p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,90, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>B 类：婚假、丧假、护理假、产假、晚育假、哺乳假</p>";
        $this->_PDF->writeHTMLCell(190, 9, 95,90, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>C类：产前假、病假</p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,98, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>D类：事假</p>";
        $this->_PDF->writeHTMLCell(190, 9, 95,98, $html, 0, 1, 0, true, 'L', true);

        $vaca_type = $arr["vaca_type"];
        $vaca_type =$vaca_type=="E"?"A":$vaca_type;

        $sumDay = 0;
        if($arr["vaca_type"] == "E"){
/*            $zero1=strtotime ($arr["entry_time"]." 00:00:00");  //入職時間
            $zero2=strtotime ($arr["start_time"]);  //請假開始時間
            $guonian=floor(($zero2-$zero1)/(60*60*24*365));
            if($guonian<1){
                $sumDay = 0;
            }elseif ($guonian<10){
                $sumDay = 5;
            }elseif ($guonian<20){
                $sumDay = 10;
            }else{
                $sumDay = 15;
            }
            $sumDay=$sumDay + floatval($arr["sumDay"]) - floatval($arr["leaveNum"]);*/
            $sumDay=floatval($arr["sumDay"]) - floatval($arr["leaveNum"]);
            $html = $sumDay+floatval($arr["log_time"])."天";
            $this->_PDF->writeHTMLCell(98, 6, 101,186, $html, 0, 1, 0, true, 'C', true);
            $html = $sumDay."天";
            $this->_PDF->writeHTMLCell(98, 6, 101,196, $html, 0, 1, 0, true, 'C', true);
        }

        $html = "休假类别";
        $this->_PDF->writeHTMLCell(20, 11, 10,115, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["vacation_name"];
        $this->_PDF->writeHTMLCell(20, 20, 10,130, $html, 0, 1, 0, true, 'C', true);
        $html = "具体<br>假种";
        $this->_PDF->writeHTMLCell(15, 11, 30,113, $html, 0, 1, 0, true, 'C', true);
        $html = $vaca_type;
        $this->_PDF->writeHTMLCell(15, 11, 30,130, $html, 0, 1, 0, true, 'C', true);
        $html = "起始日期/时间";
        $this->_PDF->writeHTMLCell(40, 11, 45,115, $html, 0, 1, 0, true, 'C', true);
        $html = "终止日期/时间";
        $this->_PDF->writeHTMLCell(40, 11, 85,115, $html, 0, 1, 0, true, 'C', true);
        if(!empty($arr["time_list"])){
            $time_y=0;
            foreach ($arr["time_list"] as $time_list){
                $html = date("Y-m-d",strtotime($time_list["start_time"]))." ".$time_list["start_time_lg"];
                $this->_PDF->writeHTMLCell(40, 5, 45,130+$time_y, $html, 0, 1, 0, true, 'C', true);
                $html = date("Y-m-d",strtotime($time_list["end_time"]))." ".$time_list["end_time_lg"];
                $this->_PDF->writeHTMLCell(40, 5, 85,130+$time_y, $html, 0, 1, 0, true, 'C', true);
                $time_y+=6;
            }
        }
        $html = "休假天数/时数";
        $this->_PDF->writeHTMLCell(30, 11, 125,115, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["log_time"]." 天";
        $this->_PDF->writeHTMLCell(30, 11, 125,130, $html, 0, 1, 0, true, 'C', true);
        $html = "请假事由";
        $this->_PDF->writeHTMLCell(45, 11, 155,115, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["leave_cause"];
        $this->_PDF->writeHTMLCell(45, 11, 155,130, $html, 0, 1, 0, true, 'C', true);
        //$html = date("Y-m-d",strtotime($arr["lcd"]));
        //$this->_PDF->writeHTMLCell(55, 6, 145,165, $html, 0, 1, 0, true, 'C', true);

        $html = "复核：";
        $this->_PDF->writeHTMLCell(45, 6, 11,176, $html, 0, 1, 0, true, 'L', true);
        $html = "人事部门意见：";
        $this->_PDF->writeHTMLCell(42, 6, 10,191, $html, 0, 1, 0, true, 'C', true);
        $html = "休假前年假天数";
        $this->_PDF->writeHTMLCell(47, 6, 52,186, $html, 0, 1, 0, true, 'L', true);
        $html = "休假后年假剩余天数";
        $this->_PDF->writeHTMLCell(47, 6, 52,196, $html, 0, 1, 0, true, 'L', true);
        $html = "签批：";
        $this->_PDF->writeHTMLCell(45, 6, 11,206, $html, 0, 1, 0, true, 'L', true);
        $html = "部门经理";
        $this->_PDF->writeHTMLCell(42, 6, 10,219, $html, 0, 1, 0, true, 'C', true);
        $html = "公司法定代表人/负责人（或依法被授权签字的代理人）";
        $this->_PDF->writeHTMLCell(42, 6, 10,230, $html, 0, 1, 0, true, 'C', true);
        $html = "区域经<br>理/总监（如适用）";
        $this->_PDF->writeHTMLCell(18, 6, 100,220, $html, 0, 1, 0, true, 'C', true);
        $html = "中国区营运<br>总监<br>（如适用）";
        $this->_PDF->writeHTMLCell(24, 6, 148,222, $html, 0, 1, 0, true, 'C', true);

/*       $this->_PDF->Line(10,228,100,228);*/
    }
	public function setPageToLeaveToEnglish($arr=array()){
        $suffix = Yii::app()->basePath;
        $this->_PDF->AddPage();
        //员工请假单
        $this->_PDF->Image("$suffix/../images/LBS_Group.jpg",92,5,25,22);
        $this->_PDF->SetFont('times', '', 18, '', true);
        $html = "Leave Application Form";
        $this->_PDF->writeHTMLCell(190, 10, 10,33, $html, 0, 1, 0, true, 'C', true);


        $this->_PDF->SetFont('times', '', 14, '', true);
        //繪製表格
        $this->_PDF->MultiCell(190,12,"",1);
        $this->_PDF->MultiCell(190,12,"",1);
        $this->_PDF->MultiCell(190,48,"",1);
        $this->_PDF->MultiCell(190,12,"",1);
        $this->_PDF->MultiCell(190,25,"",1);
        //竖线
        $this->_PDF->Line(35,43,35,79);
        $this->_PDF->Line(65,55,65,67);
        $this->_PDF->Line(90,55,90,67);
        $this->_PDF->Line(130,43,130,67);
        $this->_PDF->Line(160,43,160,67);

        $this->_PDF->Line(30,115,30,152);
        $this->_PDF->Line(45,115,45,152);
        $this->_PDF->Line(85,115,85,152);
        $this->_PDF->Line(125,115,125,152);
        $this->_PDF->Line(155,115,155,152);

        //$this->_PDF->Line(115,162,200,162);
        $this->_PDF->Line(10,79,200,79);
        $this->_PDF->Line(54,166,98,166);
        $this->_PDF->Line(145,166,200,166);

        $this->_PDF->writeHTMLCell(190, 6, 10,147, "", 0, 1, 0, true, 'L', true);
        $html = "<p><b>I hereby apply leave (see above for details) and the company and seek for approval.</b></p>";
        $this->_PDF->writeHTMLCell(190,8,"","",$html);

        $this->_PDF->MultiCell(100,8,"",0);
        $this->_PDF->MultiCell(115,8,"Employee Signature:",0,"L",false,0);
        $this->_PDF->MultiCell(100,8,"Date of:",0,"L",false,0);
        //$this->_PDF->writeHTMLCell(190, 8, 20,"", $html, 0, 1, 0, true, 'L', true);
        //繪製表格
        $this->_PDF->SetFillColor(10,10,10,10);
        $this->_PDF->writeHTMLCell(190, 8, 10,"", "", 0, 1, 0, true, 'L', true);
        $this->_PDF->MultiCell(190,10,"",1,"L",true);
        $this->_PDF->MultiCell(190,20,"",1,"L");
        $this->_PDF->MultiCell(190,10,"",1,"L",true);
        $this->_PDF->MultiCell(190,30,"",1,"L");
        $this->_PDF->MultiCell(190,2,"",0,"L");
        //竖线
        $this->_PDF->Line(52,179,52,199);
        $this->_PDF->Line(52,209,52,239);
        $this->_PDF->Line(100,179,100,199);
        $this->_PDF->Line(100,209,100,239);
        $this->_PDF->Line(118,209,118,239);
        $this->_PDF->Line(148,209,148,239);
        $this->_PDF->Line(172,209,172,239);

        $this->_PDF->Line(52,188,200,188);
        //$this->_PDF->Line(10,228,100,228);

        $this->_PDF->SetFont('times', '', 12, '', true);
        $html = "<p>Remarks:<br>";
        $html .= "1.Confirm the details of leave application form are accurate and correct.<br>";
        $html .= "2.For annual leave application equal or more than 3 days, must be submitted 2 weeks before.<br>";
        $html .= "3.Original Medical certificate shall be submitted for sick leave application,If medical prove is not provided, it will consider as personal leave.   <br>";
        $html .= "4.Leave application procedures:Staff could apply leave on LBS online system,request for approval from our Company Representative(For annual leave under 3 days, approve by Department Head. For annual leave 3 days or above, approve by Department Head & General Manager.)After Approval,HR. Department will print out the application form,Staff required to sign on the application and upload it back to LBS system leave attachment, company will keep the original application copy.<br>";
        $html .= "5.This application applies to all staff. Leave application for General Manager/Company Representative requires to take approval from Regional director.</p>";
        $this->_PDF->writeHTMLCell(190, 5, 10,240, $html, 0, 1, 0, true, 'L', true);

        $html = "Name";
        $this->_PDF->writeHTMLCell(25, 8, 10,47, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_name"];
        $this->_PDF->writeHTMLCell(90, 8, 37,47, $html, 0, 1, 0, true, 'L', true);
        $html = "Staff No.";
        $this->_PDF->writeHTMLCell(30, 8, 130,47, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_code"];
        $this->_PDF->writeHTMLCell(40, 8, 160,47, $html, 0, 1, 0, true, 'C', true);
        $html = "Date of<br>Employment";
        $this->_PDF->writeHTMLCell(30, 8, 130,57, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["entry_time"];
        $this->_PDF->writeHTMLCell(40, 8, 160,59, $html, 0, 1, 0, true, 'C', true);
        $html = "Department";
        $this->_PDF->writeHTMLCell(25, 8, 10,59, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["dept_name"];
        $this->_PDF->writeHTMLCell(34, 8, 33,59, $html, 0, 1, 0, true, 'C', true);
        $html = "Position";
        $this->_PDF->writeHTMLCell(25, 8, 65,59, $html,0 , 1, 0, true, 'C', true);
        $html = $arr["posi_name"];
        $this->_PDF->writeHTMLCell(44, 8, 88,59, $html, 0, 1, 0, true, 'C', true);
        $html = "Co. Name";
        $this->_PDF->writeHTMLCell(25, 8, 10,71, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["company_name"];
        $this->_PDF->writeHTMLCell(160, 8, 38,71, $html, 0, 1, 0, true, 'L', true);
        $signature = LeaveForm::getSignatureToStaffId($arr["employee_id"]);
        if($signature){
            $this->setSignature($signature, 60, 153, 0,16);
        }

        $html = "<p><b>Leave Category:</b></p>";
        $this->_PDF->writeHTMLCell(190, 9, 11,82, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>A:OT Leave,Annual Leave,Special Leave</p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,90, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>B:Marriage Leave,Funeral Leave,Nursing Leave,Maternity Leave</p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,96, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>C:Prenatal Leave,Sick Leave</p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,102, $html, 0, 1, 0, true, 'L', true);
        $html = "<p>D:Personal Leave </p>";
        $this->_PDF->writeHTMLCell(190, 9, 15,108, $html, 0, 1, 0, true, 'L', true);

        $vaca_type = $arr["vaca_type"];
        $vaca_type =$vaca_type=="E"?"A":$vaca_type;

        $sumDay = 0;
        if($arr["vaca_type"] == "E"){
            $sumDay=floatval($arr["sumDay"]) - floatval($arr["leaveNum"]);
            $html = $sumDay+floatval($arr["log_time"])." day";
            $this->_PDF->writeHTMLCell(98, 6, 101,182, $html, 0, 1, 0, true, 'C', true);
            $html = $sumDay." day";
            $this->_PDF->writeHTMLCell(98, 6, 101,192, $html, 0, 1, 0, true, 'C', true);
        }

        $html = "Leave<br>Category";
        $this->_PDF->writeHTMLCell(20, 11, 10,117, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["vacation_name"];
        $this->_PDF->writeHTMLCell(20, 20, 10,130, $html, 0, 1, 0, true, 'C', true);
        $html = "Leave<br>Type";
        $this->_PDF->writeHTMLCell(15, 11, 30,117, $html, 0, 1, 0, true, 'C', true);
        $html = $vaca_type;
        $this->_PDF->writeHTMLCell(15, 11, 30,130, $html, 0, 1, 0, true, 'C', true);
        $html = "Start Date/Time";
        $this->_PDF->writeHTMLCell(40, 11, 45,119, $html, 0, 1, 0, true, 'C', true);
        $html = "End Date/Time";
        $this->_PDF->writeHTMLCell(40, 11, 85,119, $html, 0, 1, 0, true, 'C', true);
        if(!empty($arr["time_list"])){
            $time_y=0;
            foreach ($arr["time_list"] as $time_list){
                $html = date("Y-m-d",strtotime($time_list["start_time"]))." ".$time_list["start_time_lg"];
                $this->_PDF->writeHTMLCell(40, 5, 45,130+$time_y, $html, 0, 1, 0, true, 'C', true);
                $html = date("Y-m-d",strtotime($time_list["end_time"]))." ".$time_list["end_time_lg"];
                $this->_PDF->writeHTMLCell(40, 5, 85,130+$time_y, $html, 0, 1, 0, true, 'C', true);
                $time_y+=6;
            }
        }
        $html = "Total Days";
        $this->_PDF->writeHTMLCell(30, 11, 125,119, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["log_time"]." day";
        $this->_PDF->writeHTMLCell(30, 11, 125,130, $html, 0, 1, 0, true, 'C', true);
        $html = "Remarks";
        $this->_PDF->writeHTMLCell(45, 11, 155,119, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["leave_cause"];
        $this->_PDF->writeHTMLCell(45, 11, 155,130, $html, 0, 1, 0, true, 'C', true);
        //$html = date("Y-m-d",strtotime($arr["lcd"]));
        //$this->_PDF->writeHTMLCell(55, 6, 145,161, $html, 0, 1, 0, true, 'C', true);

        $html = "Review:";
        $this->_PDF->writeHTMLCell(45, 6, 11,172, $html, 0, 1, 0, true, 'L', true);
        $html = "HR Dep. Remarks:";
        $this->_PDF->writeHTMLCell(42, 6, 10,186, $html, 0, 1, 0, true, 'C', true);
        $html = "Annual Leave (Before)";
        $this->_PDF->writeHTMLCell(47, 6, 52,182, $html, 0, 1, 0, true, 'L', true);
        $html = "Annual Leave (After)";
        $this->_PDF->writeHTMLCell(47, 6, 52,192, $html, 0, 1, 0, true, 'L', true);
        $html = "Approval:";
        $this->_PDF->writeHTMLCell(45, 6, 11,202, $html, 0, 1, 0, true, 'L', true);
        $html = "Company Representative";
        $this->_PDF->writeHTMLCell(42, 6, 10,220, $html, 0, 1, 0, true, 'C', true);
        $html = "Regional or Director(If Applicable)";
        $this->_PDF->writeHTMLCell(18, 6, 100,215, $html, 0, 1, 0, true, 'C', true);
        $html = "General Manager(If Applicable)";
        $this->_PDF->writeHTMLCell(24, 6, 148,217, $html, 0, 1, 0, true, 'C', true);

/*       $this->_PDF->Line(10,228,100,228);*/
    }

	//210mm×297mm
	//190mm×287mm
	public function setPageToWork($arr=array()){
        $this->_PDF->AddPage();
/*        $html = "<table border='1' width='100%'><thead><tr><th>1</th><th>2</th></tr></thead><tbody><tr><td>22</td><td>333</td></tr></tbody></table>";
        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        $this->_PDF->writeHTMLCell(63, 5, 10,55, $html, 0, 1, 0, true, '', true);*/
        //加班申请表
        $this->_PDF->SetFont('stsongstdlight', '', 18, '', true);
        $html = "加班申请表";
        $this->_PDF->writeHTMLCell(190, 10, 10,10, $html, 0, 1, 0, true, 'C', true);
        //单位名称
        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        $html = "公司名称：".$arr["company_name"];
        $this->_PDF->writeHTMLCell(95, 8, 10,20, $html, 0, 1, 0, true, 'L', true);
        //所属部门
        $html = "所属部门：".$arr["dept_name"];
        $this->_PDF->writeHTMLCell(95, 6, 105,20, $html, 0, 1, 0, true, 'L', true);


        //繪製表格
        $this->_PDF->MultiCell(190,13,"",1);
        $this->_PDF->MultiCell(190,58,"",1);
        $this->_PDF->MultiCell(190,22,"",1);
        $this->_PDF->MultiCell(190,30,"",1);
        $this->_PDF->MultiCell(190,30,"",1);
        $this->_PDF->MultiCell(190,33,"",1);
        $this->_PDF->MultiCell(190,30,"",1);
        //竖线
        $this->_PDF->Line(50,26,50,242);
        $this->_PDF->Line(78,26,78,39);
        $this->_PDF->Line(106,26,106,39);
        $this->_PDF->Line(134,26,134,39);
        $this->_PDF->Line(162,26,162,39);
        //文字填充
        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        //员工编号
        $html = "员工编号";
        $this->_PDF->writeHTMLCell(40, 13, 10,31, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_code"];
        $this->_PDF->writeHTMLCell(28, 13, 50,31, $html, 0, 1, 0, true, 'C', true);
        //姓 名
        $html = "姓 名";
        $this->_PDF->writeHTMLCell(28, 13, 78,31, $html, 0, 1, 0, true, 'C', true);
        $html = $arr["employee_name"];
        $this->_PDF->writeHTMLCell(28, 13, 106,31, $html, 0, 1, 0, true, 'C', true);
        //填表日期
        $html = "填表日期";
        $this->_PDF->writeHTMLCell(28, 13, 134,31, $html, 0, 1, 0, true, 'C', true);
        $html = date("Y/m/d",strtotime($arr["lcd"]));
        $this->_PDF->writeHTMLCell(38, 13, 162,31, $html, 0, 1, 0, true, 'C', true);
        //拟定加班时段及时间
        $html = "<p>拟定加班</p><p>日期、时段及</p><p>时间</p>";
        $this->_PDF->writeHTMLCell(40, 20, 10,55, $html, 0, 1, false, true, 'C', true);
        $html = "<p>□工作日：（年/月/日/时/分～年/月/日/时/分）</p>";
        $this->_PDF->writeHTMLCell(150, 8, 51,41, $html, 0, 1, false, true, 'L', true);
        $html = "<p>□周末休息日/工作日：（年/月/日/时/分～年/月/日/时/分）</p>";
        $this->_PDF->writeHTMLCell(150, 8, 51,57, $html, 0, 1, false, true, 'L', true);
        $html = "<p>□法定休假日：（年/月/日/时/分～年/月/日/时/分）</p>";
        $this->_PDF->writeHTMLCell(150, 8, 51,81, $html, 0, 1, false, true, 'L', true);
        //加班時間隨動
        switch ($arr["work_type"]){
            case 3: //常規加班
                $arrAddTime = $arr["addTime"];
                array_unshift($arrAddTime,array("start_time"=>$arr["start_time"],"end_time"=>$arr["end_time"]));
                $timeHeight = 16;
                $key=0;
                $timeHtml="<p>";
                foreach ($arrAddTime as $row){
                    $key++;
                    $timeHtml .= date("Y.m.d H:i",strtotime($row["start_time"]))."&nbsp;&nbsp;-&nbsp;&nbsp;".date("Y.m.d H:i",strtotime($row["end_time"]));
                    if($key%2 == 0){
                        $timeHtml .="<br>";
                    }else{
                        $timeHtml .="&nbsp;&nbsp;、&nbsp;&nbsp;";
                    }
                }
                $timeHtml.="</p>";
                break;
            case 1:
                $timeHeight = 16;
                $timeHtml = "<p>".date("Y年m月d日 H时i分",strtotime($arr["start_time"]))."&nbsp;&nbsp;～&nbsp;&nbsp;".date("Y年m月d日 H时i分",strtotime($arr["end_time"]))."</p>";
                break;
            case 2:
                $timeHeight = 40;
                $timeHtml = "<p>".date("Y年m月d日 H时i分",strtotime($arr["start_time"]))."&nbsp;&nbsp;～&nbsp;&nbsp;".date("Y年m月d日 H时i分",strtotime($arr["end_time"]))."</p>";
                break;
            default:
                $timeHeight = 0;//0:工作日  16：週末休息日   32：法定休息日
                $timeHtml = "<p>".date("Y年m月d日 H时i分",strtotime($arr["start_time"]))."&nbsp;&nbsp;～&nbsp;&nbsp;".date("Y年m月d日 H时i分",strtotime($arr["end_time"]))."</p>";
        }
        $this->_PDF->SetFont('stsongstdlight', '', 18, '', true);
        $html = "√";
        $this->_PDF->writeHTMLCell(20, 20, 50,40+$timeHeight, $html, 0, 1, false, true, 'L', true);
        if($arr["work_type"] = 3){
            $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        }else{
            $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        }
        $this->_PDF->writeHTMLCell(200, 8, 57,49+$timeHeight, $timeHtml, 0, 1, false, true, 'L', true);
        //加班事由
        $html = "加班事由";
        $this->_PDF->writeHTMLCell(40, 10, 10,106, $html, 0, 1, false, true, 'C', true);
        $html = $arr["work_cause"];
        $this->_PDF->writeHTMLCell(148, 15, 51,100, $html, 0, 1, false, true, 'L', true);

        //加班地点及工作内容
        $html = "<p>加班地点</p><br><p>及工作内容</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,127, $html, 0, 1, false, true, 'C', true);
        $html = $arr["work_address"];
        $this->_PDF->writeHTMLCell(148, 25, 51,122, $html, 0, 1, false, true, 'L', true);

        //部门主管意见
        $html = "<p>部门主管</p><br><p>意见</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,157, $html, 0, 1, false, true, 'C', true);
        /* 不需要日期及簽名
        if(!empty($arr["user_lcd"])){
            $html = date("Y年m月d日",strtotime($arr["user_lcd"]));
            $this->_PDF->writeHTMLCell(148, 8, 51,171, $html, 0, 1, false, true, 'R', true);
            $signature = WorkForm::getSignatureToStaffId($arr["user_lcu"],false);
            if($signature){
                $this->setSignature($signature, 80, 170, 0,25);
            }
        }
        */

        //公司法定代表人/负责人(或经依法授权的代理人)审批
        $html = "<p>公司法定代</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,184, $html, 0, 1, false, true, 'C', true);
        $html = "<p>表人/负责人</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,189, $html, 0, 1, false, true, 'C', true);
        $html = "<p>(或经依法授</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,194, $html, 0, 1, false, true, 'C', true);
        $html = "<p>权的代理人)</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,199, $html, 0, 1, false, true, 'C', true);
        $html = "<p>审批</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,204, $html, 0, 1, false, true, 'C', true);
        /* 不需要日期及簽名
        if(!empty($arr["area_lcd"])){
            $html = date("Y年m月d日",strtotime($arr["area_lcd"]));
            $this->_PDF->writeHTMLCell(148, 8, 51,205, $html, 0, 1, false, true, 'R', true);
            $signature = WorkForm::getSignatureToStaffId($arr["area_lcu"],false);
            if($signature){
                $this->setSignature($signature, 80, 182, 0,25);
            }
        }
        */

        //法定代表人(後期刪除，改成員工簽字
        $html = "<p>员工确认签</p><br><p>字</p>";
        $this->_PDF->writeHTMLCell(40, 10, 10,220, $html, 0, 1, false, true, 'C', true);
/*        if(!empty($arr["head_lcd"])){
            $html = date("Y年m月d日",strtotime($arr["head_lcd"]));
            $this->_PDF->writeHTMLCell(148, 8, 51,205, $html, 0, 1, false, true, 'R', true);
        }*/
        $signature = WorkForm::getSignatureToStaffId($arr["employee_id"]);
        if($signature){
            $this->setSignature($signature, 80, 215, 0,25);
        }


        //底部文字
        $this->_PDF->SetFont('stsongstdlight', '', 12, '', true);
        $html = "备注：<br>1. 拟定于工作日加班的，每日不得超过3个小时且每月不得超过36个小时，拟定于周末休息日加班的，由部门主管或部门经理尽量安排补休；<br>2. 请员工在加班日之前，提早至少一个工作日填写此申请表，并递交至所属部门主管及公司法定代表人/负责人签字后方可生效；在特殊或紧急情况下，经部门主管及法定代表人事先书面确认（包括但不限于短信、微信、邮件等书面方式），员工可先行加班，而后补填加班申请表并完成加班申请表审批手续。<br>3.  审批流程：员工在LBS系统填写此申请表，审批完成后，由公司人事专员下载并打印此申请表，再由员工签字确认，签字后的申请表再上传至员工加班申请单附件处,申请表原件由公司存档。";
        $this->_PDF->writeHTMLCell(190, 22, 10,248, $html, 0, 1, 0, true, 'L', true);
        //底部表格
/*        $this->_PDF->MultiCell(190,13,"",1);
        $this->_PDF->MultiCell(190,16,"",1);
        $this->_PDF->MultiCell(190,16,"",1);*/
        // 34 29
/*        $this->_PDF->Line(39,248,39,280);
        $this->_PDF->Line(73,248,73,264);
        $this->_PDF->Line(102,248,102,264);
        $this->_PDF->Line(136,248,136,264);
        $this->_PDF->Line(165,248,165,264);
        $this->_PDF->SetFont('stsongstdlight', '', 14, '', true);
        $html = "<p>由大中华发展及支援中心填写以下内容：</p>";
        $this->_PDF->writeHTMLCell(190, 10, 12,240, $html, 0, 1, false, true, 'L', true);
        $html = "<p>所属地区</p>";
        $this->_PDF->writeHTMLCell(29, 10, 10,254, $html, 0, 1, false, true, 'C', true);
        $html = "<p>员工编号</p>";
        $this->_PDF->writeHTMLCell(29, 10, 73,254, $html, 0, 1, false, true, 'C', true);
        $html = "<p>文件编号</p>";
        $this->_PDF->writeHTMLCell(29, 10, 136,254, $html, 0, 1, false, true, 'C', true);
        $html = "<p>备注</p>";
        $this->_PDF->writeHTMLCell(29, 10, 10,270, $html, 0, 1, false, true, 'C', true);*/
    }

    private function setSignature($signature,$x,$y,$w,$h){
/*        $suffix = Yii::app()->basePath;
        $im = imagecreatefromstring(base64_decode($signature["field_blob"]));
        $path = "$suffix/../upload/";
        if ($im !== false) {
            imagejpeg($im, $path . "test.jpg");
            imagedestroy($im);
        }
        $this->_PDF->Image($path.'test.jpg',$x,$y,$w,$h);
        unlink($path . "test.jpg");*/
    }

	public function getOutput($str="docx") {//D
        ob_end_clean();
        $this->_PDF->Output($str.".pdf", 'D');
        //$this->_PDF->Output($str.".pdf", 'I');
        exit;
	}
}
?>