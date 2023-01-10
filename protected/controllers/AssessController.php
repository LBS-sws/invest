<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class AssessController extends Controller
{
	public $function_id='ZE07';

    public function filters()
    {
        return array(
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
                'actions'=>array('new','edit','copy','sent','delete','save','audit','fileupload','fileRemove'),
                'expression'=>array('AssessController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view','fileDownload'),
                'expression'=>array('AssessController','allowReadOnly'),
            ),
            array('allow',
                'actions'=>array('ajaxCity','ajaxStaff','ajaxHistory'),
                'expression'=>array('AssessController','allowWrite'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('ZE07');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('ZE07');
    }

    public static function allowWrite() {
        return !empty(Yii::app()->user->id);
    }

    public function actionIndex($pageNum=0){
        $model = new AssessList;
        if (isset($_POST['AssessList'])) {
            $model->attributes = $_POST['AssessList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['assess_01']) && !empty($session['assess_01'])) {
                $criteria = $session['assess_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }


    public function actionNew()
    {
        $model = new AssessForm('new');
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new AssessForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new AssessForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }


    public function actionSave()
    {
        if (isset($_POST['AssessForm'])) {
            $model = new AssessForm($_POST['AssessForm']['scenario']);
            $model->attributes = $_POST['AssessForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('assess/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    public function actionCopy()
    {
        $model = new AssessForm('new');
        if (isset($_POST['AssessForm'])) {
            $model->attributes = $_POST['AssessForm'];
            $this->render('form',array('model'=>$model,));
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionAudit()
    {
        if (isset($_POST['AssessForm'])) {
            $model = new AssessForm($_POST['AssessForm']['scenario']);
            $model->attributes = $_POST['AssessForm'];
            $model->audit = true;
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('assess/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $model->audit = false;
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除
    public function actionDelete(){
        $model = new AssessForm('delete');
        if (isset($_POST['AssessForm'])) {
            $model->attributes = $_POST['AssessForm'];
            if($model->validate()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
                $this->redirect(Yii::app()->createUrl('assess/index'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','This record is already in use'));
                $this->redirect(Yii::app()->createUrl('assess/edit',array('index'=>$model->id)));
            }
        }
    }

    public function actionFileupload($doctype) {
        $model = new AssessForm();
        if (isset($_POST['AssessForm'])) {
            $model->attributes = $_POST['AssessForm'];

            $id = ($_POST['AssessForm']['scenario']=='new') ? 0 : $model->id;
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
        $model = new AssessForm();
        if (isset($_POST['AssessForm'])) {
            $model->attributes = $_POST['AssessForm'];

            $docman = new DocMan($model->docType,$model->id,'AssessForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select city from hr_assess where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row!==false) {
            $docman = new DocMan($doctype,$docId,'AssessForm');
            $docman->masterId = $mastId;
            $docman->fileDownload($fileId);
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }

    public function actionAjaxCity() {

        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $city = $_POST['city'];
            $staffList = AssessForm::getEmployeeList($city);
            unset($staffList[""]);
            echo CJSON::encode(array("status"=>1,"staffList"=>$staffList));
        }else{
            $this->redirect(Yii::app()->createUrl(''));
        }
    }

    public function actionAjaxHistory() {
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $id = $_POST['id'];
            $staff_id = $_POST['staff_id'];
            $model = new AssessForm();
            $arr = $model->getHistoryList($staff_id,$id);
            echo CJSON::encode($arr);
        }else{
            $this->redirect(Yii::app()->createUrl(''));
        }
    }

    public function actionAjaxStaff() {
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $staff = $_POST['staff'];
            $staffList = EmployeeForm::getEmployeeOneToId($staff);
            if(empty($staffList)){
                echo CJSON::encode(array("status"=>0));
            }else{
                $staffList["work_type"] = DeptForm::getDeptToId($staffList["position"]);
                echo CJSON::encode(array("status"=>1,"staffList"=>$staffList));
            }
        }else{
            $this->redirect(Yii::app()->createUrl(''));
        }
    }

    //發送郵件
    public function actionSent($pageNum =0) {
        $model = new AssessList("sent");
        if (isset($_POST['AssessList'])) {
            $model->attributes = $_POST['AssessList'];
            if($model->validate()){
                $model->sentEmail();
                Dialog::message(Yii::t('dialog','Information'), Yii::t("dialog","Save Done and Sent Notification"));
                $this->redirect(Yii::app()->createUrl('assess/index'));
            }else{
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->redirect(Yii::app()->createUrl('assess/index'));
            }
        } else {
            Dialog::message(Yii::t('dialog','Information'), "数据异常");
            $this->redirect(Yii::app()->createUrl('assess/index'));
        }
    }
}