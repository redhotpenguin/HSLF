<?php

class PushMessageController extends Controller {


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
            array('allow',
                'actions' => array('index'),
                'roles' => array('readPushMessage'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createPushMessage'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updatePushMessage'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deletePushMessage'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new PushMessage;
        $model->creation_date = date('Y-m-d h:i:s');


        if (isset($_POST['PushMessage'])) {
            $model->attributes = $_POST['PushMessage'];
            if ($model->save()) {
                if (isset($_POST['PushMessage']['tags']))
                    $model->massUpdateTags($_POST['PushMessage']['tags']);

                $this->redirect(array('update', 'id' => $model->id, 'created' => true));
            }
        }

        $this->render('editor', array(
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

        if (isset($_POST['PushMessage'])) {
            $model->attributes = $_POST['PushMessage'];
            if ($model->save()) {
                if (isset($_POST['PushMessage']['tags']))
                    $model->massUpdateTags($_POST['PushMessage']['tags']);
                else
                    $model->removeAllTagsAssociation();


                $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
            }
        }

        $this->render('editor', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via index grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new PushMessage('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PushMessage']))
            $model->attributes = $_GET['PushMessage'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PushMessage::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'push-message-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
