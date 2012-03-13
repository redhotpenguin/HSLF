<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class PushNotificationsController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {

        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'sendnotification', 'notificationsent'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PushNotifications;
        $model->create_time = date('Y-m-d H:i:s');
        $model->setAttribute('sent', 'no');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PushNotifications'])) {
            $model->attributes = $_POST['PushNotifications'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PushNotifications'])) {
            $model->attributes = $_POST['PushNotifications'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('PushNotifications');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Send a Push Notifcation
     */
    public function actionSendNotification($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['PushNotifications'])) {
            $criteria = new CDbCriteria();
            
              $states_abbr = array();
               $district_numbers = array();

            if (isset($_POST['districts'])) {
                $district_name_number = $_POST['districts'];

              
                foreach ($district_name_number as $name_number) {
                    array_push($states_abbr, substr($name_number, 0, 2));
                    array_push($district_numbers, substr($name_number, 2));
                }


                $criteria->addInCondition("state_abbr", $states_abbr);
                $criteria->addInCondition("district_number", $district_numbers);
            }

            
            $device_tokens = Application_users::model()->findAll($criteria);

            $airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);

            $message = array('aps' => array('alert' => 'hello from the Yii PHP backend'));
            //  $airship->push($message, array('0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC') , array('testTag'));


            $model->attributes = $_POST['PushNotifications'];
            $model->setAttribute('sent', 'yes');

            $model->save();
            
            //print confirmation page
            $this->actionNotificationSent($model->id, count($device_tokens));
            return false;   
        }
        else{
            $this->render('sendNotification', array(
                'model' => $model,
            ));
        }
    }
    
    
     /**
     *  Notifcation Sent Confirmation
     */
    public function actionNotificationSent($id, $notifcation_total){
        $model = $this->loadModel($id);
         $this->render('notificationSent', array(
                'model' => $model,
                'total' => $notifcation_total,
            ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PushNotifications('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PushNotifications']))
            $model->attributes = $_GET['PushNotifications'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PushNotifications::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'push-notifications-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
