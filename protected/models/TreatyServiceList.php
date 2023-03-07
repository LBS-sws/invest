<?php

class TreatyServiceList extends CListPageModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'treaty_code'=>Yii::t('treaty','treaty code'),
            'company_name'=>Yii::t('treaty','company name'),
            'city_allow'=>Yii::t('treaty','city allow'),

            'treaty_num'=>Yii::t('treaty','treaty num'),
			'apply_date'=>Yii::t('treaty','apply date'),
			'start_date'=>Yii::t('treaty','start date'),
			'end_date'=>Yii::t('treaty','treaty end date'),
			'state_type'=>Yii::t('treaty','Current status'),
            'apply_lcu'=>Yii::t('treaty','apply username'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
		$sql1 = "select a.*,b.name as city_name 
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.city_allow in ({$city_allow}) or a.apply_lcu='{$uid}')
			";
		$sql2 = "select count(a.id)
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.city_allow in ({$city_allow}) or a.apply_lcu='{$uid}')
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'treaty_code':
					$clause .= General::getSqlConditionClause('a.treaty_code',$svalue);
					break;
				case 'company_name':
					$clause .= General::getSqlConditionClause('a.company_name',$svalue);
					break;
				case 'city_allow':
					$clause .= General::getSqlConditionClause('b.name',$svalue);
					break;
				case 'apply_lcu':
					$clause .= General::getSqlConditionClause('a.apply_lcu',$svalue);
					break;
				case 'state_type':
					$clause .= General::getSqlConditionClause('a.state_type',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
            $order .= " order by {$this->orderField} ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
			    $arr = self::getStyleArr($record["state_type"]);
                $this->attr[] = array(
                    'id'=>$record['id'],
                    'treaty_code'=>$record['treaty_code'],
                    'company_name'=>$record['company_name'],
                    'apply_lcu'=>$record['apply_lcu'],
                    'city_allow'=>$record['city_name'],
                    'treaty'=>self::getTreatyDoc($record['id']),
                    'treaty_num'=>empty($record['treaty_num'])?"":$record['treaty_num'],
                    'apply_date'=>empty($record['apply_date'])?"":CGeneral::toDate($record['apply_date']),
                    'start_date'=>empty($record['start_date'])?"":CGeneral::toDate($record['start_date']),
                    'end_date'=>empty($record['end_date'])?"":CGeneral::toDate($record['end_date']),
                    'state_type'=>$arr["state_type"],
                    'color'=>$arr["color"],
                );
			}
		}
		$session = Yii::app()->session;
		$session['treatyService_c01'] = $this->getCriteria();
		return true;
	}

	public static function getTreatyDoc($treaty_id){
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("id")->from("inv_treaty_info")
            ->where("treaty_id=:id",array(":id"=>$treaty_id))->queryAll();
        $whereSql = "(a.doc_type_code='TREATY' AND a.doc_id=$treaty_id)";
        if($rows){
            foreach ($rows as $row){
                $whereSql.= " or (a.doc_type_code='TYINFO' AND a.doc_id={$row["id"]})";
            }
        }
        $count = Yii::app()->db->createCommand()->select("count(b.id)")->from("docman{$suffix}.dm_file b")
            ->leftJoin("docman{$suffix}.dm_master a","a.id = b.mast_id")
            ->where("b.remove<>'Y' and ($whereSql)",array(":id"=>$treaty_id))->queryScalar();
        return $count;

    }

	public static function getStyleArr($state_type){
        $arr = array(
            0=>array(//未使用
                "color"=>"",
                "state_type"=>Yii::t("treaty","treaty unused")
            ),
            1=>array(//合約進行中
                "color"=>"text-primary",
                "state_type"=>Yii::t("treaty","treaty service")
            ),
            2=>array(//合約已過期
                "color"=>"text-success",
                "state_type"=>Yii::t("treaty","treaty end")
            ),
            3=>array(//合約已終止
                "color"=>"text-danger",
                "state_type"=>Yii::t("treaty","treaty stop")
            ),
        );
        $exprList = TreatyInfoForm::getInfoStateList();
        if(key_exists($state_type,$arr)){
            return $arr[$state_type];
        }elseif(key_exists($state_type,$exprList)){
            return array("color"=>"text-primary","state_type"=>$exprList[$state_type]);
        }else{
            return array("color"=>"","state_type"=>"");
        }
    }

	public static function getStateStr($state_type){
	    $arr = self::getStyleArr($state_type);
	    return $arr["state_type"];
    }
}
