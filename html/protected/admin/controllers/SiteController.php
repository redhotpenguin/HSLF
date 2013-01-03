<?php

class SiteController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $data = null;
        
        //user is already authenticated
        if (Yii::app()->user->id) {
            $data = array(
                'total_item_number' => Item::model()->count(),
                'total_user_number' => MobileUser::model()->count(),
                'tenant' => Tenant::model()->findByAttributes(array("id" => Yii::app()->user->tenant_id))
            );

            $this->render('index', $data);
        } else {
            $model = new LoginForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                
                // validate user input and redirect to the admin home page if valid
                if ($model->validate() && $model->login()) {
                    $this->redirect('site/index');
                }
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionLogin() {
        if (Yii::app()->user->id) {
            $this->actionIndex();
        } else {

            $model = new LoginForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl . 'admin/');
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'publishing', 'messaging', 'administration', 'mobile', 'logout', 'project'),
                'users' => array('@'),
            ),
            array('allow', // 
                'actions' => array('index', 'login', 'error'),
                'users' => array('*'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

}