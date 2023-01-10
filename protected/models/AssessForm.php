<?php

class AssessForm extends CFormModel
{
	public $id;
	public $employee_id;
	public $work_type;
	public $city;
	public $service_effect;
	public $service_process;
	public $carefully;
	public $judge;
	public $deal;
	public $connects;
	public $obey;
	public $leadership;
	public $characters;
	public $assess;
	public $staff_type;//工種
	public $overall_effect;//整體效果


    public $no_of_attm = array(
        'assess'=>0
    );
    public $docType = 'ASSESS';
    public $docMasterId = array(
        'assess'=>0
    );
    public $files;
    public $removeFileId = array(
        'assess'=>0
    );
	public function attributeLabels()
	{//"staff_type","service_effect","service_process","carefully","judge","deal","connects","obey","leadership","overall_effect","characters","assess","lcd"
		return array(
            'work_type'=>Yii::t('contract','Leader'),
            'employee_id'=>Yii::t('contract','Employee Name'),
            'city'=>Yii::t('contract','City'),
            'email_bool'=>Yii::t('fete','email bool'),

            'service_effect'=>Yii::t('fete','service effect'),
            'service_process'=>Yii::t('fete','service process'),
            'carefully'=>Yii::t('fete','carefully'),
            'judge'=>Yii::t('fete','judge'),
            'deal'=>Yii::t('fete','deal'),
            'connects'=>Yii::t('fete','connect'),
            'obey'=>Yii::t('fete','obey'),
            'leadership'=>Yii::t('fete','leadership'),
            'characters'=>Yii::t('fete','character'),
            'assess'=>Yii::t('fete','assess'),
            'staff_type'=>Yii::t('fete','staff type'),
            'overall_effect'=>Yii::t('fete','overall effect'),
            'lcd'=>Yii::t('fete','Evaluation Time'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id,employee_id,work_type,city,service_effect,service_process,carefully,judge,deal,connects,obey,leadership,characters,assess,staff_type,overall_effect','safe'),
            array('employee_id','required'),
            array('staff_type','required'),
            array('service_effect','validateNumber'),
            array('service_process','validateNumber'),
            array('carefully','validateNumber'),
            array('judge','validateNumber'),
            array('deal','validateNumber'),
            array('connects','validateNumber'),
            array('obey','validateNumber'),
            array('leadership','validateNumber'),
            array('overall_effect','validateNumber'),
            //array('characters','numerical',"min"=>0,"max"=>10),
            array('files, removeFileId, docMasterId','safe'),
		);
	}
    public function validateTime($attribute, $params){
        if(!empty($this->end_time)&&!empty($this->start_time)){
            $date2 = strtotime($this->start_time);
            $date1 = strtotime($this->end_time);
            if($date2>$date1){
                $message = Yii::t('fete','Start time cannot be greater than end time');
                $this->addError($attribute,$message);
            }else{
                if($this->log_time <= 0){
                    $message = Yii::t('fete','Start time cannot be greater than end time');
                    $this->addError($attribute,$message);
                }
            }
        }
    }
    public function validateNumber($attribute, $params){
        $value = $this[$attribute];
        $labels = $this->getAttributeLabel($attribute);
        if(empty($value)&&$value !== 0&&$value !== '0'){
            return true;
        }
        if($this->staff_type == 3){
            $arr = explode('/',$value);
            if(count($arr)>2){
                $message = $labels."格式不正確";
                $this->addError($attribute,$message);
            }else{
                foreach ($arr as $str){
                    if (!is_numeric($str)){
                        $message = $labels."只能是數字";
                        $this->addError($attribute,$message);
                    }else if (intval($str) != floatval($str)){
                        $message = $labels."只能是整數";
                        $this->addError($attribute,$message);
                    }else if (intval($str)<0){
                        $message = $labels."不能小于0";
                        $this->addError($attribute,$message);
                    }else if (intval($str)>10){
                        $message = $labels."不能大于10";
                        $this->addError($attribute,$message);
                    }
                }
            }
        }else{
            if (!is_numeric($value)){
                $message = $labels."只能是數字";
                $this->addError($attribute,$message);
            }else if (intval($value) != floatval($value)){
                $message = $labels."只能是整數";
                $this->addError($attribute,$message);
            }else if (intval($value)<0){
                $message = $labels."不能小于0";
                $this->addError($attribute,$message);
            }else if (intval($value)>10){
                $message = $labels."不能大于10";
                $this->addError($attribute,$message);
            }
        }
    }

	public function retrieveData($index) {
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("a.*,b.name as employee_name,b.code AS employee_code,b.city AS s_city ,docman$suffix.countdoc('ASSESS',a.id) as assessdoc")
            ->from("hr_assess a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.id=:id",array(":id"=>$index))->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
                $this->id = $row['id'];
                $this->employee_id = $row['employee_id'];
                $this->work_type = $row['work_type'];
                $this->city = $row['s_city'];
                $this->service_effect = $row['service_effect'];
                $this->service_process = $row['service_process'];
                $this->carefully = $row['carefully'];
                $this->judge = $row['judge'];
                $this->deal = $row['deal'];
                $this->connects = $row['connects'];
                $this->obey = $row['obey'];
                $this->leadership = $row['leadership'];
                $this->characters = $row['characters'];
                $this->staff_type = $row['staff_type'];
                $this->assess = $row['assess'];
                $this->overall_effect = $row['overall_effect'];
                $this->no_of_attm['assess'] = $row['assessdoc'];
                break;
			}
		}
		return true;
	}

    //
    public function getHistoryList($staff_id,$id=0){
        $arr = array("status"=>1,"html"=>"");
        $rows = Yii::app()->db->createCommand()->select("*")->from("hr_assess")
            ->where("id!=:id and employee_id=:staff_id",array(":id"=>$id,":staff_id"=>$staff_id))->order("lcd desc")->queryAll();
        if($rows){
            $html = "";
            $staffType = PrizeList::getPrizeList();
            foreach ($rows as $row){
                $html.= "<tr>";
                //service_effect,service_process,carefully,judge,deal,connects,obey,leadership,overall_effect
                $html.= "<td>".date("Y-m-d",strtotime($row["lcd"]))."</td>";
                $html.= "<td data-str=''>".$staffType[$row["staff_type"]]."</td>";
                $html.= "<td data-str='overall_effect'>".$row["overall_effect"]."</td>";
                $html.= "<td data-str='service_effect'>".$row["service_effect"]."</td>";
                $html.= "<td data-str='service_process'>".$row["service_process"]."</td>";
                $html.= "<td data-str='carefully'>".$row["carefully"]."</td>";
                $html.= "<td data-str='judge'>".$row["judge"]."</td>";
                $html.= "<td data-str='deal'>".$row["deal"]."</td>";
                $html.= "<td data-str='connects'>".$row["connects"]."</td>";
                $html.= "<td data-str='obey'>".$row["obey"]."</td>";
                $html.= "<td data-str='leadership'>".$row["leadership"]."</td>";
                $html.= "<td data-str='characters'>".$row["characters"]."</td>";
                $html.= "<td data-str='assess'>".$row["assess"]."</td>";
                $html.= "<td data-str=''>".TbHtml::button(Yii::t("contract","Insert"),array("class"=>"insert_history"))."</td>";
                $html.= "</tr>";
            }
            $arr["html"] = $html;
        }else{
            $arr = array("status"=>1,"html"=>"<tr><td colspan='14'>该员工没有评估记录</td></tr>");
        }
        return $arr;
    }

    //刪除驗證
    public function deleteValidate(){
        return true;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveGoods($connection);
            $this->updateDocman($connection,'ASSESS');
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update. ('.$e->getMessage().')');
		}
	}

    protected function updateDocman(&$connection, $doctype) {
        if ($this->scenario=='new') {
            $docidx = strtolower($doctype);
            if ($this->docMasterId[$docidx] > 0) {
                $docman = new DocMan($doctype,$this->id,get_class($this));
                $docman->masterId = $this->docMasterId[$docidx];
                $docman->updateDocId($connection, $this->docMasterId[$docidx]);
            }
            $this->scenario = "edit";
        }
    }

	protected function saveGoods(&$connection) {
		$sql = '';
        switch ($this->scenario) {
            case 'delete':
                $sql = "delete from hr_assess where id = :id";
                break;
            case 'new':
                $sql = "insert into hr_assess(
							employee_id,work_type,city,service_effect,service_process,carefully,judge,deal,obey,leadership,assess,characters,staff_type,connects,overall_effect, lcu
						) values (
							:employee_id,:work_type,:city,:service_effect,:service_process,:carefully,:judge,:deal,:obey,:leadership,:assess,:characters,:staff_type,:connects,:overall_effect, :lcu
						)";
                break;
            case 'edit':
                $sql = "update hr_assess set
							employee_id = :employee_id, 
							work_type = :work_type, 
							service_effect = :service_effect, 
							service_process = :service_process, 
							carefully = :carefully, 
							judge = :judge, 
							deal = :deal, 
							connects = :connects, 
							obey = :obey, 
							leadership = :leadership, 
							characters = :characters, 
							staff_type = :staff_type, 
							assess = :assess, 
							overall_effect = :overall_effect, 
							luu = :luu
						where id = :id
						";
                break;
        }
		if (empty($sql)) return false;

        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;

        $command=$connection->createCommand($sql);
        if (strpos($sql,':id')!==false)
            $command->bindParam(':id',$this->id,PDO::PARAM_INT);
//id,employee_id,work_type,city,service_effect,service_process,carefully,judge,deal,connect,obey,leadership,character,assess
        if (strpos($sql,':employee_id')!==false)
            $command->bindParam(':employee_id',$this->employee_id,PDO::PARAM_STR);
        if (strpos($sql,':work_type')!==false)
            $command->bindParam(':work_type',$this->work_type,PDO::PARAM_STR);
        if (strpos($sql,':service_effect')!==false)
            $command->bindParam(':service_effect',$this->service_effect,PDO::PARAM_STR);
        if (strpos($sql,':service_process')!==false)
            $command->bindParam(':service_process',$this->service_process,PDO::PARAM_STR);
        if (strpos($sql,':carefully')!==false)
            $command->bindParam(':carefully',$this->carefully,PDO::PARAM_STR);
        if (strpos($sql,':judge')!==false)
            $command->bindParam(':judge',$this->judge,PDO::PARAM_STR);
        if (strpos($sql,':deal')!==false)
            $command->bindParam(':deal',$this->deal,PDO::PARAM_STR);
        if (strpos($sql,':connects')!==false)
            $command->bindParam(':connects',$this->connects,PDO::PARAM_STR);
        if (strpos($sql,':obey')!==false)
            $command->bindParam(':obey',$this->obey,PDO::PARAM_STR);
        if (strpos($sql,':leadership')!==false)
            $command->bindParam(':leadership',$this->leadership,PDO::PARAM_STR);
        if (strpos($sql,':characters')!==false)
            $command->bindParam(':characters',$this->characters,PDO::PARAM_STR);
        if (strpos($sql,':staff_type')!==false)
            $command->bindParam(':staff_type',$this->staff_type,PDO::PARAM_STR);
        if (strpos($sql,':assess')!==false)
            $command->bindParam(':assess',$this->assess,PDO::PARAM_STR);
        if (strpos($sql,':overall_effect')!==false)
            $command->bindParam(':overall_effect',$this->overall_effect,PDO::PARAM_STR);

        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$city,PDO::PARAM_STR);
        if (strpos($sql,':luu')!==false)
            $command->bindParam(':luu',$uid,PDO::PARAM_STR);
        if (strpos($sql,':lcu')!==false)
            $command->bindParam(':lcu',$uid,PDO::PARAM_STR);
        $command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
        }
		return true;
	}

    private function lenStr($id){
        $code = strval($id);
//Percy: Yii::app()->params['employeeCode']用來處理不同地區版本不同字首
        $str = Yii::app()->params['employeeCode'];
        for($i = 0;$i < 5-strlen($code);$i++){
            $str.="0";
        }
        $str .= $code;
        return $str;
    }
    //獲取城市表單
    public function getCityList(){
        $form = 'security'.Yii::app()->params['envSuffix'].'.sec_city';
        $sql = "select * from $form";
        $arr = array(
            ""=>"",
        );
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["code"]]=$row["name"];
            }
        }
        return $arr;
    }
    //獲取員工列表
    public function getEmployeeList($city=""){
        $sql = "select * from hr_employee WHERE staff_status=0";
        if(!empty($city)){
            $sql.=" AND city='$city'";
        }
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array(
            ""=>"",
        );
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]]=$row["code"]." - ".$row["name"];
            }
        }
        return $arr;
    }
    //獲取員工列表
    public function getPrizeStaffNum($city=""){
        if(empty($city)){
            return "";
        }
        //entry_time
        $date = date("Y/m/d");
        $date = date('Y/m/d', strtotime("$date -3 month"));;
        $rows = Yii::app()->db->createCommand()->select("count(*)")->from("hr_employee a")
            ->leftJoin("hr_dept b","a.position = b.id")
            ->where("a.city=:city and a.staff_status=0 and a.entry_time<='$date' and b.technician=1",array(":city"=>$city))->queryScalar();
        return $rows;
    }

	//判斷輸入框能否修改
	public function getInputBool(){
        if($this->scenario == "view"){
            return true;
        }
        return false;
    }
}
