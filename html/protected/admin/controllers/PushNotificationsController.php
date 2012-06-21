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
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'sendnotification', 'notificationsent', 'validatepush', 'gettreeview'),
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
        $model = new PushNotification;
        $model->create_time = date('Y-m-d H:i:s');
        $model->setAttribute('sent', 'no');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PushNotification'])) {
            $model->attributes = $_POST['PushNotification'];
            if ($model->save())
                $this->redirect(array('sendNotification', 'id' => $model->id));
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

        if (isset($_POST['PushNotification'])) {
            $model->attributes = $_POST['PushNotification'];
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
        $dataProvider = new CActiveDataProvider('PushNotification');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Push Notifcation Page
     */
    public function actionSendNotification($id) {

        $model = $this->loadModel($id);
        $data["pushNotificationResult"] = "";
        $this->render('sendNotification', array(
            'model' => $model,
            'data' => $data,
        ));
    }

    public function actionValidatePush($id) { // handle ajax submission of districts
        $model = $this->loadModel($id);
        $data = array();

        $audience_type = $_POST['audience_type'];
        $model->attributes = $_POST['PushNotifications'];

        switch ($audience_type) {
            case 'broadcast':
                if (isset($_POST['confirm_broadcast'])) {
                    $notifier = new UrbanAirshipNotifier();
                    $broadcast_result = $notifier->sendBroadcastNotification($model->message);
                    if ($broadcast_result['BROADCAST_IOS'] == true && $broadcast_result['BROADCAST_ANDROID'] == true) {
                        $model->sent = 'yes';
                        $message = 'Broadcast message successfuly sent';
                    } else {
                        $message = 'Couldn\'t send the broadcast message';
                    }
                } else {
                    $message = 'Broadcast confirmation required.';
                }
                break;

            case 'state_district':

                if (isset($_POST['tags']) && count($_POST['tags']) > 0) {
                    $notifier = new UrbanAirshipNotifier();

                    $tags = $_POST['tags'];
                    
                    $push_result = $notifier->sendPushNotifications($model->message, $tags);
                    if ($push_result == true) {
                        $model->sent = 'yes';
                        $message = 'Notification successfuly sent';
                    } elseif ($push_result == 'NO_USER_FOUND') {
                        $message = 'No users in that district';
                    } else {
                        $message = 'Impossible to deliver this notification';
                    }
                } else {
                    $message = 'Please select a state or a district';
                }
                break;
        }

        $model->save();


        //  $message .= '<br>' . print_r($_POST, true);
        $data['pushNotificationResult'] = $message;
        $this->renderPartial('_ajaxPushResultContent', $data, false, true);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new PushNotification('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PushNotification']))
            $model->attributes = $_GET['PushNotification'];

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
        $model = PushNotification::model()->findByPk($id);
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

    public function actionGetTreeView() {
        $this->renderPartial('_ajaxTreeView', array(), false, true);
    }

}