<?php

class TreatyServiceForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $treaty_code;
	public $treaty_num;

    public $company_name;
    public $agent_user;
    public $agent_phone;
    public $company_date;
    public $annual_money;
    public $rate_num;
    public $account_type;
    public $technician_type;
    public $sales_source;
    public $rate_government;
    public $remark;
    public $end_remark;

	public $city;
	public $city_allow;
	public $apply_date;
	public $apply_lcu;
	public $start_date;
	public $end_date;
	public $state_type;
	public $lcu;


    public $no_of_attm = array(
        'treaty'=>0,
        'tyinfo'=>0
    );
    public $docType = 'TREATY';
    public $docMasterId = array(
        'treaty'=>0,
        'tyinfo'=>0
    );
    public $files;
    public $removeFileId = array(
        'treaty'=>0,
        'tyinfo'=>0
    );

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'treaty_code'=>Yii::t('treaty','treaty code'),
            'treaty_num'=>Yii::t('treaty','treaty num'),
            'city'=>Yii::t('treaty','city'),
            'city_allow'=>Yii::t('treaty','city allow'),
            'apply_date'=>Yii::t('treaty','apply date'),
            'apply_lcu'=>Yii::t('treaty','apply username'),
            'start_date'=>Yii::t('treaty','start date'),
            'end_date'=>Yii::t('treaty','end date'),
            'state_type'=>Yii::t('treaty','treaty state'),

            'company_name'=>Yii::t('treaty','company name'),
            'company_date'=>Yii::t('treaty','company date'),
            'agent_user'=>Yii::t('treaty','agent user'),
            'agent_phone'=>Yii::t('treaty','agent phone'),
            'annual_money'=>Yii::t('treaty','annual money'),
            'rate_num'=>Yii::t('treaty','rate num'),
            'account_type'=>Yii::t('treaty','account type'),
            'technician_type'=>Yii::t('treaty','technician type'),
            'sales_source'=>Yii::t('treaty','sales source'),
            'rate_government'=>Yii::t('treaty','rate government'),
            'remark'=>Yii::t('treaty','remark'),
            'end_remark'=>Yii::t('treaty','end remark'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('id,treaty_code,treaty_num,city,city_allow,apply_date,apply_lcu,start_date,end_date,state_type,
            company_name,company_date,agent_user,agent_phone,annual_money,rate_num,account_type,
            technician_type,sales_source,rate_government,remark,end_remark','safe'),
			array('company_name,city_allow','required'),
            array('id','validateID','on'=>array("delete")),
            array('files, removeFileId, docMasterId','safe'),
		);
	}

    public function validateID($attribute, $params) {
        $id = $this->$attribute;
        $row = Yii::app()->db->createCommand()->select("id")->from("inv_treaty_info")
            ->where("treaty_id=:id",array(":id"=>$id))->queryRow();
        if($row){
            $this->addError($attribute, "这条记录已被使用无法删除");
            return false;
        }
    }

	public function retrieveData($index,$bool=true)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $uid = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $sql = "select a.*,docman$suffix.countdoc('TREATY',a.id) as treatydoc 
				from inv_treaty a
				where (a.city_allow in ({$city_allow}) or a.apply_lcu='{$uid}') and a.id='$index'
			";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ($row!==false) {
		    //id,treaty_code,treaty_num,city,city_allow,apply_date,apply_lcu,start_date,end_date,
            //state_type,company_name,company_date,agent_user,annual_money,rate_num,account_type,
            //technician_type,sales_source,rate_government,remark,end_remark
			$this->id = $row['id'];
			$this->treaty_code = $row['treaty_code'];
			$this->treaty_num = empty($row["treaty_num"])?"":$row['treaty_num'];
			$this->city = $row['city'];
			$this->city_allow = $row['city_allow'];
			$this->apply_lcu = $row['apply_lcu'];
			$this->apply_date = empty($row["apply_date"])?"":CGeneral::toDate($row["apply_date"]);
			$this->start_date = empty($row["start_date"])?"":CGeneral::toDate($row["start_date"]);
			$this->end_date = empty($row["end_date"])?"":CGeneral::toDate($row["end_date"]);
			$this->state_type = $row['state_type'];
            $this->company_name = $row['company_name'];
            $this->company_date = empty($row["company_date"])?"":CGeneral::toDate($row["end_date"]);

            $this->agent_user = $row['agent_user'];
            $this->agent_phone = $row['agent_phone'];
            $row['annual_money'] = floatval($row['annual_money']);
            $row['rate_government'] = floatval($row['rate_government']);
            $row['rate_num'] = floatval($row['rate_num']);
            $this->annual_money = empty($row['annual_money'])?"":$row['annual_money'];
            $this->rate_num = empty($row['rate_num'])?"":$row['rate_num'];
            $this->rate_government = empty($row['rate_government'])?"":$row['rate_government'];
            $this->account_type = $row['account_type'];
            $this->technician_type = $row['technician_type'];
            $this->sales_source = $row['sales_source'];
            $this->remark = $row['remark'];
            $this->end_remark = $row['end_remark'];
            $this->no_of_attm["treaty"] = $row['treatydoc'];
            return true;
		}else{
		    return false;
        }
	}

	public static function getHistoryTable($treaty_id,$ready=true){
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('TYINFO',id) as tyinfodoc")
            ->from("inv_treaty_info")
            ->where("treaty_id=:id",array(":id"=>$treaty_id))->order("history_date asc")->queryAll();
        $html = "<table class='table table-hover table-striped table-bordered'>";
        $html.="<thead><tr>";
        $colspan = 6;
        if(!$ready){
            $colspan++;
            $html.="<th width='1%'>&nbsp;</th>";
        }
        $html.="<th width='10%'>".Yii::t("treaty","history code")."</th>";
        $html.="<th width='10%'>".Yii::t("treaty","history date")."</th>";
        $html.="<th width='10%'>".Yii::t("treaty","info state")."</th>";
        $html.="<th width='35%'>".Yii::t("treaty","history matter")."</th>";
        $html.="<th width='35'>".Yii::t("treaty","remark")."</th>";
        $html.="<th width='1%'>&nbsp;</th>";
        $html.="</tr></thead><tbody>";
        if($rows){
            foreach ($rows as $row){
                if(!$ready){
                    $label = "<span class='glyphicon glyphicon-pencil'></span>";
                    $link = Yii::app()->createUrl('treatyInfo/edit',array('index'=>$row["id"],'treaty_id'=>$treaty_id));
                    $html.="<tr class='clickable-row' data-href='{$link}'>";
                    $html.="<td>";
                    $html.=TbHtml::link($label,$link);
                    $html.="</td>";
                }else{
                    $html.="<tr>";
                }
                $html.="<td class='history_code'>".$row["history_code"]."</td>";
                $html.="<td class='history_date'>".CGeneral::toDate($row["history_date"])."</td>";
                $html.="<td>".TreatyInfoForm::getInfoStateList($row["info_state"],true)."</td>";
                $html.="<td>".$row["history_matter"]."</td>";
                $html.="<td>".$row["remark"]."</td>";
                if(!empty($row["tyinfodoc"])){
                    $html.="<td class='td_end' data-id='{$row["id"]}'><span class='fa fa-paperclip'></span></td>";
                }else{
                    $html.="<td>&nbsp;</td>";
                }
                $html.="</tr>";
            }
        }else{
            $html.="<tr><td colspan='{$colspan}'>无记录</td></tr>";
        }
        $html.="</tbody>";
        $html.="<tfoot><tr><td colspan='{$colspan}' class='text-right'>";

        if(!$ready){
            $html.=TbHtml::link("<span class='fa fa-plus'></span>".Yii::t("treaty","Add History"),Yii::app()->createUrl('treatyInfo/new',array("treaty_id"=>$treaty_id)),array("class"=>"btn btn-default"));
        }else{
            $html.=TbHtml::label('<span class="fa fa-paperclip"></span> ','', array(
                    'data-toggle'=>'modal','data-target'=>'#fileuploadtreaty',)
            );
        }
        $html.="</td></tr></tfoot>";
        $html.="</table>";
        return $html;
    }

	
	public function stopData(){
        $uid = Yii::app()->user->id;
        Yii::app()->db->createCommand()->update("inv_treaty", array(
            'state_type'=>3,
            'luu'=>$uid
        ), "id=:id and state_type=2", array(':id'=>$this->id));
	}

	public function shiftData($treaty_lcu){
        $uid = Yii::app()->user->id;
        Yii::app()->db->createCommand()->update("inv_treaty", array(
            'lcu'=>$treaty_lcu,
            'luu'=>$uid
        ), "id=:id", array(':id'=>$this->id));
        $this->retrieveData($this->id,false);//刷新邮件收件人
	}

	public function getAjaxFileTable($id){
	    $this->id = $id;
        $docman = new DocMan($this->docType,$id,get_class($this));
        $docman->masterId = $this->docMasterId[strtolower($docman->docType)];
        $html = $docman->ajaxGenTableFileList();//标的主附件
        $msg = Yii::t('dialog','No File Record');
        $rtn = "<tr><td>&nbsp;</td><td colspan=2>$msg</td></tr>";
        $html = $html==$rtn?"":$html;
        $rows = Yii::app()->db->createCommand()->select("id")->from("inv_treaty_info")
            ->where("treaty_id=:id",array(":id"=>$id))->order("history_date asc")->queryAll();
        if($rows){
            $infoModel = new TreatyInfoForm("new");
            foreach ($rows as $row){ //获取记录的附件
                $docman = new DocMan($infoModel->docType,$row["id"],get_class($infoModel));
                $docman->masterId = $infoModel->docMasterId[strtolower($docman->docType)];
                $infoHtml= $docman->ajaxGenTableFileList();
                $infoHtml = $infoHtml==$rtn?"":$infoHtml;
                $html.= $infoHtml;
            }
        }
        $html=empty($html)?$rtn:$html;
        return $html;
    }


	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveDataForSql($connection);
            $this->updateDocman($connection,'TREATY');
			$transaction->commit();
		}
		catch(Exception $e) {
		    var_dump($e);
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.');
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

	protected function saveDataForSql(&$connection)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$sql = '';
		switch ($this->scenario) {
			case 'delete':
				$sql = "delete from inv_treaty where id = :id";
				break;
			case 'new':
				$sql = "insert into inv_treaty(
						city_allow, city, apply_date, apply_lcu, company_name, company_date, agent_user, agent_phone, annual_money, rate_num, account_type, technician_type, sales_source, rate_government, remark, lcu) values (
						:city_allow, :city, :apply_date, :apply_lcu, :company_name, :company_date, :agent_user, :agent_phone, :annual_money, :rate_num, :account_type, :technician_type, :sales_source, :rate_government, :remark, :lcu)";
				break;
			case 'edit':
				$sql = "update inv_treaty set 
					city_allow = :city_allow, 
					city = :city, 
					company_name = :company_name, 
					company_date = :company_date, 
					agent_user = :agent_user, 
					agent_phone = :agent_phone, 
					annual_money = :annual_money, 
					annual_money = :annual_money, 
					rate_num = :rate_num, 
					account_type = :account_type, 
					technician_type = :technician_type, 
					sales_source = :sales_source, 
					rate_government = :rate_government, 
					remark = :remark, 
					luu = :luu
					where id = :id";
				break;
		}

		$uid = Yii::app()->user->id;
        $city = Yii::app()->user->city();

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':apply_date')!==false){
            $apply_date = date("Y-m-d");
            $command->bindParam(':apply_date',$apply_date,PDO::PARAM_STR);
        }
		if (strpos($sql,':company_name')!==false)
			$command->bindParam(':company_name',$this->company_name,PDO::PARAM_STR);
		if (strpos($sql,':company_date')!==false){
            $this->company_date = empty($this->company_date)?null:$this->company_date;
            $command->bindParam(':company_date',$this->company_date,PDO::PARAM_STR);
        }
		if (strpos($sql,':agent_user')!==false)
			$command->bindParam(':agent_user',$this->agent_user,PDO::PARAM_STR);
		if (strpos($sql,':agent_phone')!==false)
			$command->bindParam(':agent_phone',$this->agent_phone,PDO::PARAM_STR);
		if (strpos($sql,':annual_money')!==false){
            $this->annual_money = $this->annual_money===""?null:$this->annual_money;
            $command->bindParam(':annual_money',$this->annual_money,PDO::PARAM_LOB);
        }
		if (strpos($sql,':rate_num')!==false){
            $this->rate_num = $this->rate_num===""?null:$this->rate_num;
            $command->bindParam(':rate_num',$this->rate_num,PDO::PARAM_LOB);
        }
		if (strpos($sql,':rate_government')!==false){
            $this->rate_government = $this->rate_government===""?null:$this->rate_government;
            $command->bindParam(':rate_government',$this->rate_government,PDO::PARAM_LOB);
        }
		if (strpos($sql,':account_type')!==false)
			$command->bindParam(':account_type',$this->account_type,PDO::PARAM_INT);
		if (strpos($sql,':technician_type')!==false)
			$command->bindParam(':technician_type',$this->technician_type,PDO::PARAM_INT);
		if (strpos($sql,':sales_source')!==false)
			$command->bindParam(':sales_source',$this->sales_source,PDO::PARAM_STR);
		if (strpos($sql,':remark')!==false)
			$command->bindParam(':remark',$this->remark,PDO::PARAM_STR);
        //id,treaty_code,treaty_num,city,city_allow,apply_date,apply_lcu,start_date,end_date,
        //state_type,company_name,company_date,agent_user,annual_money,rate_num,account_type,
        //technician_type,sales_source,rate_government,remark,end_remark
		if (strpos($sql,':city')!==false)
			$command->bindParam(':city',$this->city,PDO::PARAM_STR);
		if (strpos($sql,':city_allow')!==false)
			$command->bindParam(':city_allow',$this->city_allow,PDO::PARAM_STR);

		if (strpos($sql,':apply_lcu')!==false)
			$command->bindParam(':apply_lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->treaty_code="B{$city}".(100000+$this->id);
            Yii::app()->db->createCommand()->update('inv_treaty', array(
                'treaty_code'=>$this->treaty_code,
            ), 'id=:id', array(':id'=>$this->id));
        }

		return true;
	}

	public static function getAccountType($key=0,$bool=false){
	    $list = array(0=>Yii::t("treaty","irregular"),1=>Yii::t("treaty","normal"));
	    if($bool){
	        if(key_exists($key,$list)){
	            return $list[$key];
            }else{
	            return $key;
            }
        }
        return $list;
    }

	public static function getTechnicianType($key=0,$bool=false){
	    $list = array(0=>Yii::t("treaty","none"),1=>Yii::t("treaty","have"));
	    if($bool){
	        if(key_exists($key,$list)){
	            return $list[$key];
            }else{
	            return $key;
            }
        }
        return $list;
    }
}