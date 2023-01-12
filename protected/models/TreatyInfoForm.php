<?php

class TreatyInfoForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $treaty_id;
    public $history_code;
    public $history_date;
    public $history_matter;
    public $info_state;

	public $remark;
	public $lcu;

    public $no_of_attm = array(
        'tyinfo'=>0
    );
    public $docType = 'TYINFO';
    public $docMasterId = array(
        'tyinfo'=>0
    );
    public $files;
    public $removeFileId = array(
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
            'history_code'=>Yii::t('treaty','history code'),
            'history_date'=>Yii::t('treaty','history date'),
            'history_matter'=>Yii::t('treaty','history matter'),
            'info_state'=>Yii::t('treaty','info state'),

            'remark'=>Yii::t('treaty','remark'),
            'lcu'=>Yii::t('treaty','treaty lcu'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('id,info_state,treaty_id,history_code,history_date,history_matter,remark','safe'),
			array('treaty_id,info_state,history_date','required'),
            array('id','validateID'),
            //array('remark','validateRemark'),
            array('files, removeFileId, docMasterId','safe'),
		);
	}

    public function validateRemark($attribute, $params) {
        if($this->info_state==3&&empty($this->remark)){
            $this->addError($attribute, "标的停用时必须填写备注");
            return false;
        }
    }

    public function validateID($attribute, $params) {
	    if($this->getScenario()!="new"){
            $id = $this->$attribute;
            $city_allow = Yii::app()->user->city_allow();
            $uid = Yii::app()->user->id;
            $row = Yii::app()->db->createCommand()->select("a.treaty_id")
                ->from("inv_treaty_info a")
                ->leftJoin("inv_treaty b","a.treaty_id=b.id")
                ->where("a.id=:id and (b.city_allow in ({$city_allow}) or b.apply_lcu='{$uid}')",array(":id"=>$id))->queryRow();
            if(!$row){
                $this->addError($attribute, "数据异常，请刷新重试");
                return false;
            }else{
                $this->treaty_id = $row["treaty_id"];
            }
        }
    }

	public function retrieveNewData($treaty_id)
	{
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
        $sql = "select a.id 
				from inv_treaty a
				where (a.city_allow in ({$city_allow}) or a.apply_lcu='{$uid}') and a.id='$treaty_id'
			";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row!==false) {
            $this->treaty_id=$row["id"];
            //$this->no_of_attm["tyinfo"] = $row['tyinfodoc'];
            return true;
		}else{
		    return false;
        }
	}

	public static function getTreatyRowForId($treaty_id)
	{
        $row = Yii::app()->db->createCommand()->select("*")
            ->from("inv_treaty")
            ->where("id=:id",array(":id"=>$treaty_id))->queryRow();
        if ($row) {
            return $row;
		}else{
		    return array(
		        "company_name"=>"",
		        "agent_user"=>"",
		        "agent_phone"=>"",
		        "company_date"=>"",
		        "annual_money"=>"",
		        "rate_num"=>"",
		        "account_type"=>"",
		        "technician_type"=>"",
		        "sales_source"=>"",
		        "rate_government"=>"",
            );
        }
	}

	public function retrieveData($index)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;

        $row = Yii::app()->db->createCommand()->select("a.*,docman$suffix.countdoc('TYINFO',a.id) as tyinfodoc")
            ->from("inv_treaty_info a")
            ->leftJoin("inv_treaty b","a.treaty_id=b.id")
            ->where("a.id=:id and (b.city_allow in ({$city_allow}) or b.apply_lcu='{$uid}')",array(":id"=>$index))->queryRow();
		if ($row!==false) {
		    //id,treaty_code,treaty_name,month_num,treaty_num,city,apply_date,start_date,end_date,state_type
			$this->id = $row['id'];
			$this->treaty_id = $row['treaty_id'];
            $this->history_date = empty($row["history_date"])?"":CGeneral::toDate($row["history_date"]);
            $this->history_code = $row['history_code'];
            $this->history_matter = $row['history_matter'];
            $this->remark = $row['remark'];
            $this->info_state = $row['info_state'];
            $this->no_of_attm["tyinfo"] = $row['tyinfodoc'];
            return true;
		}else{
		    return false;
        }
	}

	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveDataForSql($connection);
			$this->saveService();
            $this->updateDocman($connection,'TYINFO');
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

	protected function saveService(){
        $updateArr = array(
            "start_date"=>null,
            "treaty_num"=>0,
            "state_type"=>1,
            "end_date"=>null,
            "end_remark"=>null,
        );
	    $row = Yii::app()->db->createCommand()->select("min(history_date) as min_date,count(id) as treaty_num")
        ->from("inv_treaty_info")->where("treaty_id=:id",array(":id"=>$this->treaty_id))->queryRow();
	    if($row){
            $updateArr["start_date"]=$row["min_date"];
            $updateArr["treaty_num"]=$row["treaty_num"];
	        $maxRow = Yii::app()->db->createCommand()->select("history_date,info_state,remark")->from("inv_treaty_info")
                ->where("treaty_id=:id",array(":id"=>$this->treaty_id))->order("history_date desc")->queryRow();
	        if($maxRow&&$maxRow["info_state"]!=1){
                $updateArr["state_type"]=$maxRow["info_state"];
                $updateArr["end_remark"]=$maxRow["remark"];
                $updateArr["end_date"]=$maxRow["history_date"];
            }
        }

        Yii::app()->db->createCommand()->update('inv_treaty', $updateArr, 'id=:id', array(':id'=>$this->treaty_id));
    }

	protected function saveDataForSql(&$connection)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$sql = '';
		switch ($this->scenario) {
			case 'delete':
				$sql = "delete from inv_treaty_info where id = :id";
				break;
			case 'new':
				$sql = "insert into inv_treaty_info(
						treaty_id, history_date, history_matter, info_state, remark, lcu) values (
						:treaty_id, :history_date, :history_matter, :info_state, :remark, :lcu)";
				break;
			case 'edit':
				$sql = "update inv_treaty_info set 
					history_date = :history_date, 
					history_matter = :history_matter, 
					info_state = :info_state, 
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
		if (strpos($sql,':treaty_id')!==false)
			$command->bindParam(':treaty_id',$this->treaty_id,PDO::PARAM_INT);
		if (strpos($sql,':info_state')!==false)
			$command->bindParam(':info_state',$this->info_state,PDO::PARAM_INT);
		if (strpos($sql,':history_date')!==false)
			$command->bindParam(':history_date',$this->history_date,PDO::PARAM_STR);
		if (strpos($sql,':history_matter')!==false)
			$command->bindParam(':history_matter',$this->history_matter,PDO::PARAM_STR);
        if (strpos($sql,':remark')!==false)
            $command->bindParam(':remark',$this->remark,PDO::PARAM_STR);

		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->history_code="H".(100000+$this->treaty_id+$this->id);
            Yii::app()->db->createCommand()->update('inv_treaty_info', array(
                'history_code'=>$this->history_code,
            ), 'id=:id', array(':id'=>$this->id));
        }

		return true;
	}

	public static function getInfoStateList($key="",$bool=false){
	    $list = array(
	        1=>Yii::t("treaty","treaty service"),
	        2=>Yii::t("treaty","treaty end"),
	        3=>Yii::t("treaty","treaty stop"),
        );
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
