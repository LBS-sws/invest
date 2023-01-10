<?php

class Counter {
	public static function countConfReq() {
		$rtn = 0;

		$wf = new WorkflowPayment;
		$wf->connection = Yii::app()->db;
		$list = $wf->getPendingRequestIdList('PAYMENT', 'PB', Yii::app()->user->id);
		$items = empty($list) ? array() : explode(',',$list);
		$rtn = count($items);

		return $rtn;
	}

	public static function countApprReq() {
		$rtn = 0;

		$wf = new WorkflowPayment;
		$wf->connection = Yii::app()->db;
		$list = $wf->getPendingRequestIdList('PAYMENT', 'PA', Yii::app()->user->id);
		$items = empty($list) ? array() : explode(',',$list);
		$rtn = count($items);

		return $rtn;
	}
	
	public static function countReimb() {
		$rtn = 0;
		
		$wf = new WorkflowPayment;
		$wf->connection = Yii::app()->db;
		$list1 = $wf->getPendingRequestIdList('PAYMENT', 'PR', Yii::app()->user->id);
		$items = empty($list1) ? array() : explode(',',$list1);
		$rtn = count($items);
		
		$list2 = $wf->getPendingRequestIdList('PAYMENT', 'QR', Yii::app()->user->id);
		$items = empty($list2) ? array() : explode(',',$list2);
		$rtn += count($items);
		
		return $rtn;
	}
	
	public static function countSign() {
		$rtn = 0;
		
		$wf = new WorkflowPayment;
		$wf->connection = Yii::app()->db;
		$list = $wf->getPendingRequestIdList('PAYMENT', 'PS', Yii::app()->user->id);
		$items = empty($list) ? array() : explode(',',$list);
		$rtn = count($items);
		
		return $rtn;
	}

//加班审核(部門)
    public static function getWorkOne() {
        $staff_id = Yii::app()->user->id;
        $staffList = BindingForm::getEmployeeListToUsername();
        $department = empty($staffList)?0:$staffList["department"];
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =1 AND b.department=:department",array(":department"=>$department))->queryScalar();
        return $count;
    }
//请假审核(部門)
    public static function getLeaveOne() {
        $staff_id = Yii::app()->user->id;
        $staffList = BindingForm::getEmployeeListToUsername();
        $department = empty($staffList)?0:$staffList["department"];
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =1 AND b.department=:department",array(":department"=>$department))->queryScalar();
        return $count;
    }
//加班审核(人事)
    public static function getWorkTwo() {
        $staff_id = Yii::app()->user->id;
        $city = Yii::app()->user->city();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =5 AND b.city = '$city'")->queryScalar();
        return $count;
    }
//请假审核(人事)
    public static function getLeaveTwo() {
        $staff_id = Yii::app()->user->id;
        $city = Yii::app()->user->city();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =5 AND b.city = '$city'")->queryScalar();
        return $count;
    }
//员工录入(社保信息)
    public static function getStaffInsert() {
        $city = Yii::app()->user->city();
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_employee")
            ->where("city='$city' AND staff_status in (3,4)")->queryScalar();
        return $count;
    }
//簽署合同
    public static function getSignContract() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_sign_contract a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("b.city IN ($city_allow) AND a.status_type IN (-1,0)")->queryScalar();
        return $count;
    }
//加班审核(員工)
    public static function getWorkThree() {
        $staff_id = Yii::app()->user->id;
        $city = Yii::app()->user->city();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =2 AND b.city = '$city'")->queryScalar();
        return $count;
    }
//请假审核(員工)
    public static function getLeaveThree() {
        $staff_id = Yii::app()->user->id;
        $city = Yii::app()->user->city();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =2 AND b.city = '$city'")->queryScalar();
        return $count;
    }
//入職審核(審核)
    public static function getStaffAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_employee")
            ->where("city in ($city_allow) AND staff_status = 2")->queryScalar();
        return $count;
    }
//變更審核(審核)
    public static function getStaffChange() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_employee_operate")
            ->where("finish != 1 AND staff_status = 2 AND city IN ($city_allow)")->queryScalar();
        return $count;
    }
//簽署合同(審核)
    public static function getSignAudit() {
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_sign_contract a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.status_type=2")->queryScalar();
        return $count;
    }
//加班审核(審核)
    public static function getWorkFour() {
        $staff_id = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_work a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =3 AND b.city in ($city_allow)")->queryScalar();
        return $count;
    }
//请假审核(審核)
    public static function getLeaveFour() {
        $staff_id = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("hr_binding hb","hb.employee_id = b.id")
            ->where("hb.user_id != '$staff_id' and a.status in (1) AND a.z_index =3 AND b.city in ($city_allow)")->queryScalar();
        return $count;
    }
//奖金审核(審核)
    public static function getRewardAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_employee_reward")
            ->where("city IN ($city_allow) AND status =1")->queryScalar();
        return $count;
    }
//锦旗审核(審核)
    public static function getPrizeAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_prize a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where(" a.status =1 AND b.city IN ($city_allow) ")->queryScalar();
        return $count;
    }
//優化人才評估(考核)
    public static function getReviewHandle() {
        $row = Yii::app()->db->createCommand()->select("employee_id")->from("hr_binding")
            ->where('user_id=:user_id',array(':user_id'=>Yii::app()->user->id))->queryRow();
        $employee_id = $row?$row["employee_id"]:0;
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_review_h a")
            ->leftJoin("hr_review b","a.review_id = b.id")
            ->leftJoin("hr_employee c","c.id = b.employee_id")
            ->where(" c.staff_status = 0 and a.handle_id =:handle_id and a.status_type in (1,4)",array(":handle_id"=>$employee_id))->queryScalar();
        return $count;
    }
//考核申請(老總第二次提交)
    public static function getBossApply() {
        $row = Yii::app()->db->createCommand()->select("employee_id")->from("hr_binding")
            ->where('user_id=:user_id',array(':user_id'=>Yii::app()->user->id))->queryRow();
        $employee_id = $row?$row["employee_id"]:0;
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_boss_audit")
            ->where("employee_id=:employee_id and status_type in (3,4)",array(":employee_id"=>$employee_id))->queryScalar();
        return $count;
    }
//考核審批（繞生）
    public static function getBossOneAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_boss_audit a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.status_type in (1,5) and b.city IN ($city_allow) AND a.boss_type = 3")->queryScalar();
        return $count;
    }
//考核審批(總監)
    public static function getBossTwoAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_boss_audit a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.status_type in (1,5) and b.city IN ($city_allow) AND a.boss_type = 1")->queryScalar();
        return $count;
    }
//考核審批(副總監)
    public static function getBossThreeAudit() {
        $city_allow = Yii::app()->user->city_allow();
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_boss_audit a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.status_type in (1,5) and b.city IN ($city_allow) AND a.boss_type = 2")->queryScalar();
        return $count;
    }
//考核審批(中央支援需給員工評分)
    public static function getSupportApply() {
        $city = Yii::app()->user->city;
        $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_apply_support a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.apply_city='$city' and status_type in (14,5,13)")->queryScalar();
        return $count;
    }
//審核中央技术支持(中央技术支持)
    public static function getSupportAudit() {
        $count = Yii::app()->db->createCommand()->select("count(*)")->from("hr_apply_support")
            ->where("status_type IN (6,2,4)")->queryScalar();
        return $count;
    }
//审核心意信(意见反馈)
    public static function getLetterAudit() {
        $count = 0;
	    if(Yii::app()->user->validRWFunction('HL02')){
            $city_allow = Yii::app()->user->city_allow();
            $count = Yii::app()->db->createCommand()->select("count(a.id)")->from("hr_letter a")
                ->leftJoin("hr_employee b","a.employee_id = b.id")
                ->where("a.state IN (1,3) and b.city IN ($city_allow)")->queryScalar();
        }
        return $count;
    }
}

?>