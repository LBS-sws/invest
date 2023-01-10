<?php

class CityForm extends CFormModel
{
	public $id;
	public $city;
	public $name;
	public $z_index;

	public function attributeLabels()
	{
        return array(
            'name'=>Yii::t('contract','City'),
            'city'=>Yii::t('contract','City'),
            'z_index'=>Yii::t('fete','level'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id, city,name,z_index','safe'),
            array('city','required'),
            array('z_index','required'),
            array('city','validateCity'),
		);
	}


    public function validateCity($attribute, $params){
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("code")->from("security$suffix.sec_city")
            ->where('code=:code',array(':code'=>$this->city))->queryRow();
        if($rows){
            $rows = Yii::app()->db->createCommand()->select("id")->from("hr_city")
                ->where('city=:city',array(':city'=>$this->city))->queryRow();
            if($rows){
                $this->id = $rows["id"];
                $this->setScenario("edit");
            }else{
                $this->setScenario("new");
            }
        }else{
            $message = Yii::t('contract','City'). Yii::t('contract',' Did not find');
            $this->addError($attribute,$message);
        }
    }

	public function retrieveData($index) {
        $suffix = Yii::app()->params['envSuffix'];
		$rows = Yii::app()->db->createCommand()->select("a.name,a.code,b.*")->from("security$suffix.sec_city a")
            ->leftJoin("hr_city b","a.code = b.city")
            ->where("a.code=:city",array(":city"=>$index))->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
                $row['z_index'] = empty($row['z_index'])?1:$row['z_index'];
                $this->id = $row['id'];
                $this->city = $row['code'];
                $this->name = $row['name'];
                $this->z_index = $row['z_index'];
                break;
			}
		}
		return true;
	}

    //刪除驗證
    public function deleteValidate(){
        return false;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveGoods($connection);
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update. ('.$e->getMessage().')');
		}
	}

	protected function saveGoods(&$connection) {
		$sql = '';
        switch ($this->scenario) {
            case 'delete':
                $sql = "delete from hr_city where id = :id";
                break;
            case 'new':
                $sql = "insert into hr_city(
							city,z_index, lcu
						) values (
							:city,:z_index, :lcu
						)";
                break;
            case 'edit':
                $sql = "update hr_city set
							z_index = :z_index, 
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
        //log_bool,max_log,sub_bool,sub_multiple
        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
        if (strpos($sql,':z_index')!==false)
            $command->bindParam(':z_index',$this->z_index,PDO::PARAM_INT);

        if (strpos($sql,':luu')!==false)
            $command->bindParam(':luu',$uid,PDO::PARAM_STR);
        if (strpos($sql,':lcu')!==false)
            $command->bindParam(':lcu',$uid,PDO::PARAM_STR);
        $command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->scenario = "edit";
        }
		return true;
	}
}
