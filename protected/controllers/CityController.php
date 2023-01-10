<?php

/**
 * Created by PhpStorm.
 * User: 城市等級設置
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class CityController extends Controller
{
	public $function_id='ZC09';

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
                'actions'=>array('edit','save'),
                'expression'=>array('CityController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view'),
                'expression'=>array('CityController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('ZC09');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('ZC09');
    }

    public function actionIndex($pageNum=0){
        $model = new CityList;
        if (isset($_POST['CityList'])) {
            $model->attributes = $_POST['CityList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['city_01']) && !empty($session['city_01'])) {
                $criteria = $session['city_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }


    public function actionEdit($index)
    {
        $model = new CityForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new CityForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }


    public function actionSave()
    {
        if (isset($_POST['CityForm'])) {
            $model = new CityForm($_POST['CityForm']['scenario']);
            $model->attributes = $_POST['CityForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('city/edit',array('index'=>$model->city)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

/*    public function actionTest(){
        $test = new RptPennantCuList();
        $test->criteria = array(
            "year"=>"2018",
            "month"=>"8",
            "CITY"=>"SH",
            "STAFFS"=>"",
        );
        $test->retrieveData();
        var_dump($test->data);
    }*/
}