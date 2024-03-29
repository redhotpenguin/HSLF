<?php

class SiteController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $data = null;

//user is already authenticated
//@bug: Yii::app()->user->id is sometimes a string. Ex: 'jonas'
        if (Yii::app()->user->id) {

            $user = Yii::app()->user->getModel(array('tenants'));

            if ($user->tenants)
                $tenants = $user->tenants;
            else
                $tenants = array();

            $options = array(
                'tenants' => $tenants
            );
            $this->render('index', $options);
        } else {
            $model = new LoginForm;

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];

                // validate user input and redirect to the client home page if valid
                if ($model->validate() && $model->login()) {
                    $this->redirect("/client");
                }
            }
            $this->render('login', array('model' => $model));
        }
    }

    public function actionHome() {
        $tenant = Yii::app()->user->getLoggedInUserTenant();

        $data = array(
            'tenantSettings' => $tenant->getSettingRelation(),
            'userCount' => MobileUser::model()->count(),
            'tenantDisplayName' => $tenant->display_name,
            'isAdmin' => Yii::app()->user->hasPermission('admin'),
        );


        $this->render('home', $data);
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
     * This is the action to handle exceptions.
     */
    public function actionError() {
        $error = Yii::app()->errorHandler->error;

        $code = 500;
        $message = $error['message'];


        if ($error['type'] == 'CDbException') {
            switch ($error['errorCode']) {
                case 23502:
                case 23503:
                    $message = "This item is being used by something else and can not be deleted.";
                    break;

                case 23505:
                    $message = "A field with the same value already exists.";
                    break;

                default: $message = "Internal error";
            }
        }


        if (Yii::app()->request->isAjaxRequest) {

            echo $message;

            Yii::app()->end();
        }

        switch ($error['code']) {
            case null:
            case 404:
                $this->render('error404', array('error' => $error));
                break;

            default:
                $this->render('error', array('code' => $code, 'message' => $message));
                break;
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect('/client');
    }

    public function actionMaintenance() {
        $this->render('maintenance');
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'home', 'administration', 'mobile', 'logout', 'project', 'maintenance'),
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