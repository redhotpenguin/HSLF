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

            $user = Yii::app()->user->getModel();

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
                    $this->redirect("/admin");
                }
            }
// display the login form
            $this->render('login', array('model' => $model));
        }
    }

    public function actionHome() {
        $tenant = Yii::app()->user->getCurrentTenant();

        $tenantUserId = Yii::app()->user->getTenantUserId();

        if (Yii::app()->authManager->checkAccess('manageBallotItems', $tenantUserId))
            $itemCount = Item::model()->count();
        else
            $itemCount = null;

        if (Yii::app()->authManager->checkAccess('manageMobileUsers', $tenantUserId))
            $mobileUserCount = MobileUser::model()->count();
        else
            $mobileUserCount = null;


        $data = array(
            'itemCount' => $itemCount,
            'mobileUserCount' => $mobileUserCount,
            'tenant' => $tenant,
            'tenantUserId' => $tenantUserId
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
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        if (Yii::app()->request->isAjaxRequest) {
            if ($error['type'] == 'CDbException' && $error['errorCode'] == 23502) {
                echo 'This resource is used by something else and can not be deleted.';
            }
        }
        else
            $this->render('error', array('error' => $error));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect('/admin');
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'home', 'publishing', 'messaging', 'administration', 'mobile', 'logout', 'project'),
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