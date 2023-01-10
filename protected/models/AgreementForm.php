<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class AgreementForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $type=0;
	public $name;
	public $city;
	public $docx_url;
	public $file;
	public $word_html;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('staff','Record ID'),
			'name'=>Yii::t('contract','Agreement Name'),
			'type'=>Yii::t('contract','Status'),
			'file'=>Yii::t('contract','Agreement File'),
			'city'=>Yii::t('misc','City'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, name, type, docx_url, file, city','safe'),
			array('city','required'),
			array('name','required'),
			array('name','validateName'),
			array('type','required'),
            array('file', 'file', 'types'=>'docx', 'allowEmpty'=>false, 'maxFiles'=>1,'on'=>"new"),
            array('file', 'file', 'types'=>'docx', 'allowEmpty'=>true, 'maxFiles'=>1,'on'=>"edit"),
		);
	}

	public function validateName($attribute, $params){
        $city = $this->city;
        $rows = Yii::app()->db->createCommand()->select()->from("hr_agreement")
            ->where('id!=:id and name=:name and city=:city  ', array(':id'=>$this->id,':name'=>$this->name,':city'=>$this->city))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Agreement Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

    public function getAgreementUrl(){
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_agreement")->where("city IN ($city_allow) or type = 1")->order('id desc')->queryRow();
        if($rows){
            return $rows["docx_url"];
        }else{
            return "";
        }
    }

    //協議刪除時驗證
    public function validateDelete(){
        return true;
    }

//根據id獲取文檔地址
	public function getDocxUrlToId($index)
	{
        $rows = Yii::app()->db->createCommand()->select()->from("hr_agreement")
            ->where('id=:id', array(':id'=>$index))->queryAll();
		if (count($rows) > 0){
		    return $rows[0];
		}
		return false;
	}

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_agreement")
            ->where("id=:id and (city IN ($city_allow) or type = 1) ", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->name = $row['name'];
				$this->docx_url = $row['docx_url'];
                $this->type = $row['type'];
                $this->city = $row['city'];
                $word = new MyWord();
                $word->setDocx(Yii::app()->basePath."/../".$row["docx_url"]);
                // 将内容存入$docx变量中
                $this->word_html = $word->extract();
                $word->closeFile();
				break;
			}
		}
		return true;
	}
	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveStaff($connection);
			$transaction->commit();
			$this->resetType();//重置啟用狀態
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.');
		}
	}

	protected function saveStaff(&$connection)
	{
		$sql = '';
        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from hr_agreement where id = :id";
				break;
			case 'new':
				$sql = "insert into hr_agreement(
							name, type, docx_url, city, lcu, lcd
						) values (
							:name, :type, :docx_url, :city, :lcu, :lcd
						)";
				break;
			case 'edit':
				$sql = "update hr_agreement set
							name = :name, 
							type = :type, 
							city = :city, 
							docx_url = :docx_url,
							lud = :lud,
							luu = :luu 
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':name')!==false)
			$command->bindParam(':name',$this->name,PDO::PARAM_STR);
		if (strpos($sql,':type')!==false)
			$command->bindParam(':type',$this->type,PDO::PARAM_STR);
		if (strpos($sql,':docx_url')!==false)
			$command->bindParam(':docx_url',$this->docx_url,PDO::PARAM_STR);

        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcd')!==false)
			$command->bindParam(':lcd',date('Y-m-d H:i:s'),PDO::PARAM_STR);
		if (strpos($sql,':lud')!==false)
			$command->bindParam(':lud',date('Y-m-d H:i:s'),PDO::PARAM_STR);

		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->scenario = "edit";
        }
        return true;
	}

	//自動完成啟用狀態的變更
	private function resetType(){
        if($this->type == 1){
            Yii::app()->db->createCommand()->update('hr_agreement', array(
                'type'=>0,
            ), 'id!=:id and city=:city', array(':id'=>$this->id,":city"=>$this->city));
        }
    }
}
