<?php

class LookupController extends Controller
{
	public $interactive = false;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'enforceRegisteredStation',
			'enforceSessionExpiration', 
			'enforceNoConcurrentLogin',
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('company','supplier','staff','cityex','staffEmailEx','staffAllex','product','companyex','supplierex','staffex','productex','template',
						'account','accountex','applytemplate'
					),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionCompany($search)
	{
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(left(concat(code,space(8)),8),name) as value from swoper$suffix.swo_company
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$data = TbHtml::listData($result, 'id', 'value');
		echo TbHtml::listBox('lstlookup', '', $data, array('size'=>'15', 'multiple'=>true));
	}

	public function actionCompanyEx($search) {
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, code, name, cont_name, cont_phone, address from swoper$suffix.swo_company
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>substr($record['code'].str_repeat(' ',8),0,8).$record['name'],
						'contact'=>trim($record['cont_name']).'/'.trim($record['cont_phone']),
						'address'=>$record['address'],
					);
			}
		}
		print json_encode($result);
	}
	
	public function actionSupplier($search)
	{
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(left(concat(code,space(8)),8),name) as value from swoper$suffix.swo_supplier
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$data = TbHtml::listData($result, 'id', 'value');
		echo TbHtml::listBox('lstlookup', '', $data, array('size'=>'15', 'multiple'=>true));
	}

	public function actionSupplierEx($search) {
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, code, name, cont_name, cont_phone, address from swoper$suffix.swo_supplier
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>substr($record['code'].str_repeat(' ',8),0,8).$record['name'],
						'contact'=>trim($record['cont_name']).'/'.trim($record['cont_phone']),
						'address'=>$record['address'],
					);
			}
		}
		print json_encode($result);
	}
	
	public function actionStaff($search)
	{
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
		$searchx = str_replace("'","\'",$search);

		$sql = "select id, concat(name, ' (', code, ')') as value from swoper$suffix.swo_staff
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'
				and leave_dt is null or leave_dt=0 or leave_dt > now() ";
		$result1 = Yii::app()->db->createCommand($sql)->queryAll();

		$sql = "select id, concat(name, ' (', code, ')',' ".Yii::t('app','(Resign)')."') as value from swoper$suffix.swo_staff
				where (code like '%".$searchx."%' or name like '%".$searchx."%') and city='".$city."'
				and  leave_dt is not null and leave_dt<>0 and leave_dt <= now() ";
		$result2 = Yii::app()->db->createCommand($sql)->queryAll();
		
		$result = array_merge($result1, $result2);
		$data = TbHtml::listData($result, 'id', 'value');
		echo TbHtml::listBox('lstlookup', '', $data, array('size'=>'15',));
	}

	public function actionStaffEx($search)
	{
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql ="b.name like '%$searchx%' and b.city in ($city_allow)";
        $records = Yii::app()->db->createCommand()->select("b.*")
            ->from("hr_binding a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("b.name like '%$searchx%' and b.city in ($city_allow)")->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>$record['name'],
					);
			}
		}
		print json_encode($result);
	}

	public function actionCityEx($search)
	{
		$suffix = Yii::app()->params['envSuffix'];
        $result = array();
        $searchx = str_replace("'","\'",$search);
        $records = Yii::app()->db->createCommand()->select("a.code,a.name")
            ->from("security$suffix.sec_city a")
            ->leftJoin("security$suffix.sec_user b","a.incharge = b.username")
            ->where("(b.email !='' or b.email is not null) and a.name like '%$searchx%'")->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['code'],
						'value'=>$record['name'],
					);
			}
		}
		print json_encode($result);
	}

	public function actionStaffAllEx($search)
	{
//		$suffix = Yii::app()->params['envSuffix'];
		$suffix = '_w';
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql ="b.name like '%$searchx%' and b.city in ($city_allow)";
        $records = Yii::app()->db->createCommand()->select("b.*")->from("hr_employee b")
            ->where("b.name like '%$searchx%' and b.city in ($city_allow) and b.staff_status = 0")->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>$record['name'],
					);
			}
		}
		print json_encode($result);
	}

	public function actionStaffEmailEx($search)
	{
        //$city_allow = Yii::app()->user->city_allow();
		$searchx = str_replace("'","\'",$search);
        $suffix = Yii::app()->params['envSuffix'];
        $records = Yii::app()->db->createCommand()->select("username as id,disp_name as value")->from("security$suffix.sec_user")
            ->where("disp_name like '%$searchx%' and status = 'A'")->queryAll();
        if (count($records) > 0) {
            foreach ($records as $k=>$record) {
                $result[] = array(
                    'id'=>$record['id'],
                    'value'=>$record['value'],
                );
            }
        }
        print json_encode($result);
	}

	public function actionProduct($search)
	{
		$city = '99999';	//Yii::app()->user->city();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(left(concat(code,space(8)),8),description) as value from swo_product
				where (code like '%".$searchx."%' or description like '%".$searchx."%') and city='".$city."'";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$data = TbHtml::listData($result, 'id', 'value');
		echo TbHtml::listBox('lstlookup', '', $data, array('size'=>'15',));
	}

	public function actionProductEx($search)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = '99999';	//Yii::app()->user->city();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(left(concat(code,space(8)),8),description) as value from swoper_w.swo_product
				where (code like '%".$searchx."%' or description like '%".$searchx."%') and city='".$city."'";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>$record['value'],
					);
			}
		}
		print json_encode($result);
	}

	public function actionAccount($search)
	{
		$city = Yii::app()->user->city();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(acct_name,'(',acct_no,')') as value from swo_product
				where (acct_no like '%".$searchx."%' or acct_name like '%".$searchx."%') and city='".$city."'";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$data = TbHtml::listData($result, 'id', 'value');
		echo TbHtml::listBox('lstlookup', '', $data, array('size'=>'15',));
	}

	public function actionAccountEx($search)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
		$result = array();
		$searchx = str_replace("'","\'",$search);
		$sql = "select id, concat(acct_name,'(',acct_no,')') as value from acc_account
				where (acct_name like '%".$searchx."%' or acct_no like '%".$searchx."%') and city='".$city."' or city='99999'
				and id <> 2";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'value'=>$record['value'],
					);
			}
		}
		print json_encode($result);
	}

	public function actionTemplate() {
        $city = Yii::app()->user->city();
		$result = array();
		$sql = "select id, tem_name from hr_template
				where city='$city'
			";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$result[] = array(
						'id'=>$record['id'],
						'name'=>$record['tem_name'],
					);
			}
		}
		print json_encode($result);
	}

    public function actionApplytemplate($id) {
	    if(!is_numeric($id)||empty($id)){
            print json_encode(array());
        }else{
            $city = Yii::app()->user->city();
            $sql = "select tem_str from hr_template
				where city='$city' AND id='$id'
			";
            $records = Yii::app()->db->createCommand($sql)->queryRow();
            $lists = array();
            if ($records) {
                $lists = explode(",",$records["tem_str"]);
            }
            print json_encode($lists);
        }
    }

//	public function actionSystemDate()
//	{
//		echo CHtml::tag( date('Y-m-d H:i:s'));
//		Yii::app()->end();
//	}

}
