<?php

class DistrictController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('dynamicdistrictnumber', 'dynamicdistrict', 'exportCSV'),
                'roles' => array('publisher'),
            ),
            array(// restrict State to admins only
                'allow',
                'actions' => array('create', 'delete', 'update', 'index'),
                'roles' => array('manageDistricts'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'udate' page.
     */
    public function actionCreate() {
        $model = new District;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['District'])) {
            $model->attributes = $_POST['District'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, 'created' => true));
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

        if (isset($_POST['District'])) {
            $model->attributes = $_POST['District'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
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
        $model = new District('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['District']))
            $model->attributes = $_GET['District'];

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
        $model = District::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'district-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Dynamic Dropdown for state/district
     */
    public function actionDynamicDistrict() {
        if (!isset($_POST['model']))
            return;


        $model = $_GET['model'];
        $params = array(
            'order' => 'number ASC',
        );


        $data = District::model()->findAllByAttributes(array('state_id' => $_POST[$model]['state_id']), $params);

        $data = CHtml::listData($data, 'id', 'number');

        foreach ($data as $id => $district) {
            echo $t = CHtml::tag('option', array('value' => $id), CHtml::encode($district), true);
        }
    }

    // print a list of district tag <select>
    public function actionDynamicDistrictNumber() {

        if (!isset($_POST['state_id']) || !isset($_POST['district_type']))
            return;

        $state_id = $_POST['state_id'];
        $district_type = $_POST['district_type'];

        $params = array(
            'order' => 'number ASC',
        );

        $data = District::model()->findAllByAttributes(array('state_id' => $state_id, 'type' => $district_type), $params);

        $data = CHtml::listData($data, 'id', 'display_name');
        asort($data);

        foreach ($data as $id => $district) {
            if (empty($district))
                $district = 'N/A';

            echo CHtml::tag('option', array('value' => $id), CHtml::encode($district), true);
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('ext.csv.ESCVExport');
        $csv = new ESCVExport(District::model()->findAll());

        $content = $csv->toCSV();

        Yii::app()->getRequest()->sendFile('districts.csv', $content, "text/csv", false);
    }

}
