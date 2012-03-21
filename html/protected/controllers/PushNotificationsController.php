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
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'sendnotification', 'notificationsent', 'updateajax'),
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
        $data["pushNotificationResult"] = "";
        $this->render('sendNotification', array(
            'model' => $model,
            'data' => $data,
        ));
    }

    public function actionUpdateAjax($id) { // handle ajax submission of districts
        $data = array();
        $model = $this->loadModel($id);

        if (isset($_POST['PushNotifications']) && isset($_POST['district_ids'])) {
           
            $criteria = new CDbCriteria();
            $model->attributes = $_POST['PushNotifications'];
            $district_numbers = $_POST['district_ids'];
            $criteria->addInCondition("district", $district_numbers);

            $application_users = Application_users::model()->findAll($criteria);

            if ( ($application_users_number = count($application_users) ) > 0 ) { // application_users found
                $push_result = UrbanAirshipNotifier::send_push_notifications($application_users, $model->message);

                if ($push_result === true) {
                    $model->setAttribute('sent', 'yes');
                     $data["pushNotificationResult"] = "$application_users_number notifications successfuly sent!";
                }else{
                     $data["pushNotificationResult"] = "An error has occured";
                }   
              
           
             }else{
                 $data["pushNotificationResult"] = 'No users in that district';
            }



            $model->save();

           
        } else {
            $data["pushNotificationResult"] = "Please select a district!";
        }



        $this->renderPartial('_ajaxPushResultContent', $data, false, true);
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
