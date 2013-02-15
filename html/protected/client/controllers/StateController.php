<?php

class StateController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'exportCSV'),
                'roles' => array('readState'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createState'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateState'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteState'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     */
    public function actionCreate() {
        $model = new State;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['State'])) {
            $model->attributes = $_POST['State'];
            try {
                if ($model->save()) {
                    $this->redirect(array('update', 'id' => $model->id, 'created' => true));
                }
            } catch (Exception $e) {
                error_log("State controller:" . $e->getMessage());
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

        if (isset($_POST['State'])) {
            $model->attributes = $_POST['State'];
            try {
                if ($model->save())
                    $this->redirect(array('update',
                        'id' => $model->id, 'updated' => true));
            } catch (Exception $e) {
                error_log("State controller:" . $e->getMessage());
            }
            $this->redirect(array('view', 'id' => $model->abbr));
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
                $this->redirect(isset($_POST['returnUrl'
                        ]) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new State('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['State']))
            $model->attributes = $_GET['State'];

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
        $model = State::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'state-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('ext.csv.ESCVExport');
        $csv = new ESCVExport(State::model()->findAll());
        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('states.csv', $content, "text/csv", false);
    }

}