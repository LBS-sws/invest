<?php

class AgreementList extends CListPageModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'id'=>Yii::t('contract','ID'),
			'city'=>Yii::t('contract','City'),
			'name'=>Yii::t('contract','Agreement Name'),
			'type'=>Yii::t('contract','Status'),
		);
	}

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
		$sql1 = "select * from hr_agreement 
                where (city IN ($city_allow) or type = 1) 
			";
		$sql2 = "select count(id)
				from hr_agreement 
				where (city IN ($city_allow) or type = 1) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'name':
					$clause .= General::getSqlConditionClause('name',$svalue);
					break;
				case 'city':
					$clause .= General::getSqlConditionClause('city',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		
		$list = array();
		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
                    'city'=>CGeneral::getCityName($record["city"]),
					'type'=>empty($record['type'])?Yii::t("contract","local"):Yii::t("contract","default")
				);
			}
		}
		$session = Yii::app()->session;
		$session['agreement_01'] = $this->getCriteria();
		return true;
	}

}
