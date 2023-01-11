<?php

class TreatyInfoController extends Controller
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
				'actions'=>array('new','edit','delete','save','fileRemove','fileupload','fileDownload'),
				'expression'=>array('TreatyInfoController','allowReadWrite'),
			),
			array('allow',
				'actions'=>array('AjaxFileTable'),
				'expression'=>array('TreatyInfoController','allowAjax'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionNew($treaty_id)
    {
        $model = new TreatyInfoForm('new');
        if (!$model->retrieveNewData($treaty_id)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }


	public function actionSave()
	{
		if (isset($_POST['TreatyInfoForm'])) {
			$model = new TreatyInfoForm($_POST['TreatyInfoForm']['scenario']);
			$model->attributes = $_POST['TreatyInfoForm'];
			if ($model->validate()) {
				$model->saveData();
				$model->scenario = 'edit';
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
				$this->redirect(Yii::app()->createUrl('treatyInfo/edit',array('index'=>$model->id)));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}

	
	public function actionEdit($index)
	{
		$model = new TreatyInfoForm('edit');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionDelete()
	{
		$model = new TreatyInfoForm('delete');
		if (isset($_POST['TreatyInfoForm'])) {
			$model->attributes = $_POST['TreatyInfoForm'];
			if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
                $this->redirect(Yii::app()->createUrl('treatyService/edit',array("index"=>$model->treaty_id)));
			} else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model));
			}
		}
	}

    public function actionAjaxFileTable($id=0) {
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $model = new TreatyInfoForm();
            $docman = new DocMan($model->docType,$id,get_class($model));
            echo CJSON::encode(array("status"=>1,"html"=>$docman->ajaxGenTableFileList()));
        }else{
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
    }

    public function actionFileupload($doctype) {
        $model = new TreatyInfoForm();
        if (isset($_POST['TreatyInfoForm'])) {
            $model->attributes = $_POST['TreatyInfoForm'];
            $id = ($_POST['TreatyInfoForm']['scenario']=='new') ? 0 : $model->id;
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
        $model = new TreatyInfoForm();
        if (isset($_POST['TreatyInfoForm'])) {
            $model->attributes = $_POST['TreatyInfoForm'];
            $docman = new DocMan($model->docType,$model->id,'TreatyInfoForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $row = Yii::app()->db->createCommand()->select("b.city_allow,b.apply_lcu")->from("inv_treaty_info a")
            ->leftJoin("inv_treaty b","a.treaty_id=b.id")
            ->where("a.id=:id",array(":id"=>$docId))->queryRow();
        if ($row!==false) {
            $citylist = Yii::app()->user->city_allow();
            $uid = Yii::app()->user->id;
            if (strpos($citylist, $row['city_allow']) !== false||$row["apply_lcu"]==$uid) {
                $docman = new DocMan($doctype,$docId,'TreatyInfoForm');
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

	public static function allowAjax() {
        return Yii::app()->user->validFunction('TH01');
	}
}
