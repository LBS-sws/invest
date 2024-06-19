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
        $city_allow = General::getCityAllowAll();
        $uid = Yii::app()->user->id;
		$sql1 = "select a.*,b.name as city_name 
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.lcu_city in ({$city_allow}) or a.apply_lcu='{$uid}')
			";
		$sql2 = "select count(a.id)
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.lcu_city in ({$city_allow}) or a.apply_lcu='{$uid}')
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

	public function excelDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
        $city_allow = General::getCityAllowAll();
        $uid = Yii::app()->user->id;
		$sql1 = "select a.*,b.name as city_name 
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.lcu_city in ({$city_allow}) or a.apply_lcu='{$uid}')
			";
		$sql2 = "select count(a.id)
				from inv_treaty a
				LEFT JOIN security{$suffix}.sec_city b on a.city_allow=b.code
				where (a.lcu_city in ({$city_allow}) or a.apply_lcu='{$uid}')
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
		    $number_no=0;
			foreach ($records as $k=>$record) {
			    $number_no++;
			    $historyList = $this->getExcelHistory($record["id"]);
                $temp = array(
                    'id'=>$record['id'],
                    'number_no'=>$number_no,
                    'company_name'=>$record['company_name'],
                    'city'=>$record['city'],
                    'city_allow'=>$record['city_name'],
                    'company_date'=>$record['company_date'],
                    'rate_government'=>$record['rate_government']===null?$record['rate_government']:(floatval($record['rate_government'])."%"),
                    'rate_num'=>$record['rate_num']===null?$record['rate_num']:(floatval($record['rate_num'])."%"),
                    'annual_money'=>$record['annual_money']===null?$record['annual_money']:(floatval($record['annual_money'])."万"),
                    'capital_text'=>$record['capital_text']===null?$record['capital_text']:(floatval($record['capital_text'])."万"),
                    'agent_user'=>$record['agent_user'],
                    'agent_phone'=>$record['agent_phone'],
                    'account_type'=>TreatyServiceForm::getAccountType($record['account_type'],true),
                    'technician_type'=>TreatyServiceForm::getTechnicianType($record['technician_type'],true),
                    'sales_source'=>$record['sales_source'],
                    'lbs_city'=>TreatyServiceForm::getLBSCityOption($record['lbs_city'],true),
                    'holder_text'=>$record['holder_text'],
                    'trait_text'=>$record['trait_text'],
                    'company_city'=>"",
                    'appeal_text'=>$record['appeal_text'],
                    'history_text'=>$historyList["text"],
                    'next_work'=>"",
                    'other_text'=>"",
                    'project_form'=>"",
                );
                $this->attr[] = $temp;
			}
		}
		return true;
	}

	private function getExcelHistory($treaty_id){
	    $list = array("text"=>"");
        $rows = Yii::app()->db->createCommand()->select()->from("inv_treaty_info")
            ->where("treaty_id=:treaty_id",array(":treaty_id"=>$treaty_id))->queryAll();
        if($rows){
            foreach ($rows as $row){
                $text = $row["history_date"].":\n";
                $text.= "跟进状态：".TreatyInfoForm::getInfoStateList($row["info_state"],true)."\n";
                $text.= "参与人：".$row["participant"]."\n";
                $text.= "记录事项：".$row["history_matter"]."\n";
                $text.= "备注：".$row["remark"];
                $list["text"].=empty($list["text"])?"":"\n";
                $list["text"].=$text;
            }
        }
        return $list;
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
        if(key_exists($state_type,$arr)){
            return $arr[$state_type];
        }elseif(empty($state_type)){
            return array("color"=>"","state_type"=>"");
        }else{
            $stateStr = TreatyInfoForm::getInfoStateList($state_type,true);
            return array("color"=>"text-primary","state_type"=>$stateStr);
        }
    }

	public static function getStateStr($state_type){
	    $arr = self::getStyleArr($state_type);
	    return $arr["state_type"];
    }

    private function getTopArr(){
        $topList=array(
            "number_no"=>array("name"=>Yii::t("treaty","number no"),"width"=>8,"height"=>32,"background"=>"#f7fd9d"),//序号
            "company_name"=>array("name"=>Yii::t("treaty","company name"),"width"=>13,"background"=>"#f7fd9d"),//企业名称
            "city"=>array("name"=>Yii::t("treaty","city"),"width"=>10,"background"=>"#f7fd9d"),//城市
            "city_allow"=>array("name"=>Yii::t("treaty","city allow"),"width"=>10,"background"=>"#f7fd9d"),//区域
            "company_date"=>array("name"=>Yii::t("treaty","company date"),"width"=>15,"background"=>"#f7fd9d"),//企业成立日期
            "rate_government"=>array("name"=>Yii::t("treaty","rate government"),"width"=>15,"background"=>"#f7fd9d"),//政府项目占比
            "agent_user"=>array("name"=>Yii::t("treaty","agent user"),"width"=>15,"background"=>"#f7fd9d"),//法定代表人
            "agent_phone"=>array("name"=>Yii::t("treaty","agent phone"),"width"=>15,"background"=>"#f7fd9d"),//联系电话
            "rate_num"=>array("name"=>Yii::t("treaty","rate num"),"width"=>13,"background"=>"#f7fd9d"),//纯利率
            "account_type"=>array("name"=>Yii::t("treaty","account type"),"width"=>15,"background"=>"#f7fd9d"),//会计操作是否正规
            "technician_type"=>array("name"=>Yii::t("treaty","technician type"),"width"=>15,"background"=>"#f7fd9d"),//技术团队是否自有
            "sales_source"=>array("name"=>Yii::t("treaty","sales source"),"width"=>15,"background"=>"#f7fd9d"),//销售拓客渠道
            "lbs_city"=>array("name"=>Yii::t("treaty","lbs city"),"width"=>15,"background"=>"#f7fd9d"),//是否为LBS空白城市

            "capital_text"=>array("name"=>Yii::t("treaty","capital text"),"width"=>15,"background"=>"#f7fd9d"),//注册资本
            "holder_text"=>array("name"=>Yii::t("treaty","holder text"),"width"=>15,"background"=>"#f7fd9d"),//股东名称及股比
            "annual_money"=>array("name"=>Yii::t("treaty","annual money"),"width"=>15,"background"=>"#f7fd9d"),//年生意额
            "trait_text"=>array("name"=>Yii::t("treaty","trait text"),"width"=>15,"background"=>"#f7fd9d"),//客户类别/特点
            "company_city"=>array("name"=>Yii::t("treaty","company city"),"width"=>15,"background"=>"#f7fd9d"),//客户所在城市
            "appeal_text"=>array("name"=>Yii::t("treaty","appeal text"),"width"=>30,"background"=>"#f7fd9d"),//对方诉求
            "history_text"=>array("name"=>Yii::t("treaty","history text"),"width"=>30,"background"=>"#f7fd9d"),//目前所处阶段
            "next_work"=>array("name"=>Yii::t("treaty","next work"),"width"=>30,"background"=>"#f7fd9d"),//下步工作
            "other_text"=>array("name"=>Yii::t("treaty","other text"),"width"=>30,"background"=>"#f7fd9d"),//其他（备注）
            "project_form"=>array("name"=>Yii::t("treaty","project form"),"width"=>15,"background"=>"#f7fd9d"),//项目来源
        );

        return $topList;
    }

    public function downExcel(){
        $excelData = $this->attr;
        $headList = $this->getTopArr();
        $excel = new DownExcel();
        $excel->SetHeaderTitle(Yii::t("app","Treaty Service"));
        //$excel->SetHeaderString($this->start_date." ~ ".$this->end_date);
        $excel->init();
        $excel->setExcelHeader($headList);
        $excel->setExcelData($excelData,$headList);
        $excel->setFreezePane("C5");
        $excel->outExcel(Yii::t("app","Treaty Service"));
    }
}
