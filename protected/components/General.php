<?php
// Common Functions

class General extends CGeneral {

/* SAMPLE CODE	
// ===========
	public static function getAcctTypeList()
	{
		$list = array();
		$sql = "select id, acct_type_desc from acc_account_type order by acct_type_desc";
		$rows = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$list[$row['id']] = $row['acct_type_desc'];
			}
		}
		return $list;
	}
*/
	public function getUpdateDate() {
		$file = Yii::app()->basePath.'/config/lud.php';
		if (file_exists($file)) {
			$lud = require($file);
			return $lud;
		} else {
			return '2016/01/01';
		}
	}
	
    /*
     * 獲取必須測驗的測驗單id
     */
    public static function getQuizIdForMust(){
        $suffix = Yii::app()->params['envSuffix'];
        $quiz_id = Yii::app()->db->createCommand()
            ->select("id")->from("quiz$suffix.exa_quiz")
            ->order("join_must desc,id asc")->queryScalar();
        return $quiz_id?$quiz_id:0;
    }

    /*
     * 判斷系統位置
     * @return int  0：大陸。 1：台灣。2：新加坡。 3：吉隆坡
     */
    public static function SystemIsCN(){
        $suffix = Yii::app()->params['envSuffix'];
        $value = Yii::app()->db->createCommand()->select("set_value")
            ->from("hr$suffix.hr_setting")->where("set_name='systemId'")->queryScalar();
        return $value?$value:0;
    }

    /*
     * 加载日报表系统的SysBlock文件
     * @return str
     */
    public static function includeDrsSysBlock(){
        $systemList = require(Yii::app()->basePath.'/config/system.php');
        foreach ($systemList as $row){
            if($row["name"]=="Daily Report"){//读取日报表系统的公共文件
                $objName = end(explode("/",$row["webroot"]));
                $configPath = dirname(Yii::app()->basePath)."/../{$objName}/protected";
                include_once($configPath."/components/SysBlock.php");
                return true;
            }
        }
    }

    public static function getCityAllowAll(){
        $city_allow = Yii::app()->user->city_allow();
        $city = Yii::app()->user->city();
        $suffix = Yii::app()->params['envSuffix'];
        $sql = "select ka_bool from security{$suffix}.sec_city where code='{$city}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if($row&&$row["ka_bool"]==2){//当前账号为区域性
            $cityArea = self::getCityArea($city);
            $city_allow.=empty($city_allow)?"":",";
            $city_allow.="'".implode("','",$cityArea)."'";
            return $city_allow;
        }else{
            return $city_allow;
        }
    }

    public static function getCityArea($city){
        $list = array();
        $list[]=$city;
        $suffix = Yii::app()->params['envSuffix'];
        $sql = "select code from security{$suffix}.sec_city where region='{$city}' AND ka_bool=2";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if($rows){//当前账号为区域性
            foreach ($rows as $row){
                if(!in_array($row["code"],$list)){
                    $temp = self::getCityArea($row["code"]);
                    $list = array_merge($list,$temp);
                }
            }
        }
        return $list;
    }
}

?>