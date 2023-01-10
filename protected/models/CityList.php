<?php

class CityList extends CListPageModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
            'city'=>Yii::t('contract','City'),
            'city_name'=>Yii::t('contract','City'),
            'z_index'=>Yii::t('fete','level'),
		);
	}

	public function retrieveDataByPage($pageNum=1)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $city_allow = Yii::app()->user->city_allow();
		$sql1 = "select a.name,a.code,b.* from security$suffix.sec_city a 
                LEFT JOIN hr_city b ON a.code = b.city 
                where a.code != '' 
			";
		$sql2 = "select count(a.code)
				from security$suffix.sec_city a 
                LEFT JOIN hr_city b ON a.code = b.city 
                where a.code != '' 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'z_index':
					$clause .= General::getSqlConditionClause('b.z_index',$svalue);
					break;
                case 'city_name':
                    $clause .= ' and a.code in '.WordForm::getCityCodeSqlLikeName($svalue);
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

		$this->attr = array();
		if (count($records) > 0) {
		    $cityIndexList = $this->getCityIndexList();
			foreach ($records as $k=>$record) {
                $record['z_index'] = empty($record['z_index'])?1:$record['z_index'];
				$this->attr[] = array(
					'id'=>$record['id'],
					'city'=>$record['code'],
					'city_name'=>$record['name'],
					'z_index'=>$cityIndexList[$record['z_index']],
				);
			}
		}
		$session = Yii::app()->session;
		$session['city_01'] = $this->getCriteria();
		return true;
	}

	function getCityIndexList(){
	    return array(
	        ""=>"",
	        1=>Yii::t("fete","professional"),
	        2=>Yii::t("fete","preliminary"),
        );
    }

    function getLevelToCity($city){
	    $arr = array(
            1=>Yii::t("fete","professional"),
            2=>Yii::t("fete","preliminary"),
        );
        $rows = Yii::app()->db->createCommand()->select("z_index")->from("hr_city")
            ->where("city=:city",array(":city"=>$city))->queryRow();
        if($rows){
            return $arr[$rows["z_index"]];
        }else{
            $arr[1];
        }
    }
}
