<?php

class TreatyServiceController extends Controller
{
	public $function_id='TH01';
	
	public function filters()
	{
		return array(
			'enforceRegisteredStation',
			'enforceSessionExpiration', 
			'enforceNoConcurrentLogin',
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow', 
				'actions'=>array('new','edit','delete','save','fileRemove','fileupload'),
				'expression'=>array('TreatyServiceController','allowReadWrite'),
			),
			array('allow', 
				'actions'=>array('index','view','fileDownload','ajaxFileTable'),
				'expression'=>array('TreatyServiceController','allowReadOnly'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($pageNum=0) 
	{
		$model = new TreatyServiceList();
		if (isset($_POST['TreatyServiceList'])) {
			$model->attributes = $_POST['TreatyServiceList'];
		} else {
			$session = Yii::app()->session;
			if (isset($session['treatyService_c01']) && !empty($session['treatyService_c01'])) {
				$criteria = $session['treatyService_c01'];
				$model->setCriteria($criteria);
			}
		}
		$model->determinePageNum($pageNum);
		$model->retrieveDataByPage($model->pageNum);
		$this->render('index',array('model'=>$model));
	}


	public function actionSave()
	{
		if (isset($_POST['TreatyServiceForm'])) {
			$model = new TreatyServiceForm($_POST['TreatyServiceForm']['scenario']);
			$model->attributes = $_POST['TreatyServiceForm'];
			if ($model->validate()) {
				$model->saveData();
				$model->scenario = 'edit';
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
				$this->redirect(Yii::app()->createUrl('treatyService/edit',array('index'=>$model->id)));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}

	public function actionView($index)
	{
		$model = new TreatyServiceForm('view');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionNew()
	{
		$model = new TreatyServiceForm('new');
		$this->render('form',array('model'=>$model,));
	}
	
	public function actionEdit($index)
	{
		$model = new TreatyServiceForm('edit');
		if (!$model->retrieveData($index)) {
            $this->redirect(Yii::app()->createUrl('treatyService/index'));
		} else {
			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionDelete()
	{
		$model = new TreatyServiceForm('delete');
		if (isset($_POST['TreatyServiceForm'])) {
			$model->attributes = $_POST['TreatyServiceForm'];
			if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
                $this->redirect(Yii::app()->createUrl('treatyService/index'));
			} else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model));
			}
		}
	}

	public function actionStop()
	{
		$model = new TreatyServiceForm('stop');
		if (isset($_POST['TreatyServiceForm'])) {
			$model->attributes = $_POST['TreatyServiceForm'];
			if($model->retrieveData($model->id)){
                $model->stopData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('treaty','Record Stop'));
                $update = Yii::app()->user->validRWFunction('TH02')?"edit":"view";
                $this->redirect(Yii::app()->createUrl('treatyStop/'.$update,array("index"=>$model->id)));
            }
		}
	}

	public function actionShift()
	{
        $model = new TreatyServiceForm('shift');
        if (isset($_POST['TreatyServiceForm'])) {
            $model->attributes = $_POST['TreatyServiceForm'];
            $treaty_lcu = key_exists("treaty_lcu",$_POST)?$_POST["treaty_lcu"]:"";
            if($model->retrieveData($model->id)&&!empty($treaty_lcu)){
                $model->shiftData($treaty_lcu);
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('treatyService/edit',array('index'=>$model->id)));
            }
        }
	}

    public function actionAjaxFileTable($id=0) {
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $model = new TreatyServiceForm();
            $html = $model->getAjaxFileTable($id);
            echo CJSON::encode(array("status"=>1,"html"=>$html));
        }else{
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
    }

    public function actionFileupload($doctype) {
        $model = new TreatyServiceForm();
        if (isset($_POST['TreatyServiceForm'])) {
            $model->attributes = $_POST['TreatyServiceForm'];
            $id = ($_POST['TreatyServiceForm']['scenario']=='new') ? 0 : $model->id;
            $docman = new DocMan($model->docType,$id,get_class($model));
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            if (isset($_FILES[$docman->inputName])) $docman->files = $_FILES[$docman->inputName];
            $docman->fileUpload();
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileRemove($doctype) {
        $model = new TreatyServiceForm();
        if (isset($_POST['TreatyServiceForm'])) {
            $model->attributes = $_POST['TreatyServiceForm'];
            $docman = new DocMan($model->docType,$model->id,'TreatyServiceForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select city_allow,apply_lcu from inv_treaty where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row!==false) {
            $citylist = Yii::app()->user->city_allow();
            $uid = Yii::app()->user->id;
            if (strpos($citylist, $row['city_allow']) !== false||$row["apply_lcu"]==$uid) {
                $docman = new DocMan($doctype,$docId,'TreatyServiceForm');
                $docman->masterId = $mastId;
                $docman->fileDownload($fileId);
            } else {
                throw new CHttpException(404,'Access right not match.');
            }
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }
	
	public static function allowReadWrite() {
		return Yii::app()->user->validRWFunction('TH01');
	}
	
	public static function allowReadOnly() {
		return Yii::app()->user->validFunction('TH01');
	}
}
