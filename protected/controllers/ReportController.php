<?php
class ReportController extends Controller
{
	protected static $actions = array(
        'salessummary'=>'YB04',
        'overtimelist'=>'YB02',
        'pennantexlist'=>'YB05',
        'pennantculist'=>'YB06',
        'reviewlist'=>'YB07',
        'leavelist'=>'YB03',
        'estimated'=>'YB08',
        'pinReport'=>'YB09',
        'bossReport'=>'YB10',
    );
	
	public function filters()
	{
		return array(
			'enforceRegisteredStation',
			'enforceSessionExpiration', 
			'enforceNoConcurrentLogin',
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules() {
		$act = array();
		foreach ($this->action as $key=>$value) { $act[] = $key; }
		return array(
			array('allow', 
				'actions'=>$act,
				'expression'=>array('ReportController','allowExecute'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionSalessummary() {
		$this->function_id = self::$actions['salessummary'];
		Yii::app()->session['active_func'] = $this->function_id;
		
		$model = new ReportY01Form;
		if (isset($_POST['ReportY01Form'])) {
			$model->attributes = $_POST['ReportY01Form'];
			if ($model->validate()) {
				$model->addQueueItem();
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
			}
		}
		$this->render('form_y01',array('model'=>$model));
	}

    public function actionOvertimelist() {
		$this->function_id = self::$actions['overtimelist'];
		Yii::app()->session['active_func'] = $this->function_id;
		
        $model = new ReportY02Form;
        if (isset($_POST['ReportY02Form'])) {
            $model->attributes = $_POST['ReportY02Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y02',array('model'=>$model));
    }

    public function actionLeavelist() {
		$this->function_id = self::$actions['leavelist'];
		Yii::app()->session['active_func'] = $this->function_id;
		
        $model = new ReportY03Form;
        if (isset($_POST['ReportY03Form'])) {
            $model->attributes = $_POST['ReportY03Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y03',array('model'=>$model));
    }

    public function actionEstimated() {
		$this->function_id = self::$actions['estimated'];
		Yii::app()->session['active_func'] = $this->function_id;

        $model = new ReportEstimatedForm;
        if (isset($_POST['ReportEstimatedForm'])) {
            $model->attributes = $_POST['ReportEstimatedForm'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('estimated',array('model'=>$model));
    }

    public function actionBossReport() {
		$this->function_id = self::$actions['bossReport'];
		Yii::app()->session['active_func'] = $this->function_id;

        $model = new ReportBossAuditForm();
        if (isset($_POST['ReportBossAuditForm'])) {
            $model->attributes = $_POST['ReportBossAuditForm'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('bossAudit',array('model'=>$model));
    }

    public function actionPennantexlist() {
		$this->function_id = self::$actions['pennantexlist'];
		Yii::app()->session['active_func'] = $this->function_id;
		
        $model = new ReportY05Form;
        $model->start_dt = date("Y/m/d");
        $model->end_dt = date("Y/m/d",strtotime("+1 month"));
        $model->fields = 'start_dt,end_dt,staffs,staffs_desc';
        if (isset($_POST['ReportY05Form'])) {
            $model->attributes = $_POST['ReportY05Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y05',array('model'=>$model));
    }

    public function actionPinReport() {
		$this->function_id = self::$actions['pinReport'];
		Yii::app()->session['active_func'] = $this->function_id;

        $model = new ReportY05Form;
        $model->id = "RptPinReport";
        $model->name = Yii::t('app','Pin Report');
        $model->start_dt = date("Y/m/d");
        $model->end_dt = date("Y/m/d",strtotime("+1 month"));
        $model->fields = 'start_dt,end_dt,staffs,staffs_desc';
        if (isset($_POST['ReportY05Form'])) {
            $model->attributes = $_POST['ReportY05Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y05',array('model'=>$model));
    }

    public function actionPennantculist() {
		$this->function_id = self::$actions['pennantculist'];
		Yii::app()->session['active_func'] = $this->function_id;
		
        $model = new ReportY05Form;
        $model->id = 'RptPennantCuList';
        $model->name = Yii::t('app','Pennants cumulative List');
        if (isset($_POST['ReportY05Form'])) {
            $model->attributes = $_POST['ReportY05Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y06',array('model'=>$model));
    }

    public function actionReviewlist() {
		$this->function_id = self::$actions['reviewlist'];
		Yii::app()->session['active_func'] = $this->function_id;

        $model = new ReportY06Form;
        if (isset($_POST['ReportY06Form'])) {
            $model->attributes = $_POST['ReportY06Form'];
            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_y07',array('model'=>$model));
    }

	public static function allowExecute() {
		return Yii::app()->user->validFunction(self::$actions[Yii::app()->controller->action->id]);
	}
}
?>
