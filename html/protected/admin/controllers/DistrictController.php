<?php

class DistrictController extends Controller {

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

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('dynamicdistrictnumber', 'dynamicdistrict', 'exportCSV'),
                'users' => array('@'),
            ),
            array(// restrict State to admins only
                'allow',
                'actions' => array('create', 'delete', 'update', 'admin', 'index', 'view'),
                'users' => array('@'),
                'expression' => 'isset($user->role) && ($user->role==="admin")'
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
        $model = new District;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['District'])) {
            $model->attributes = $_POST['District'];
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

        if (isset($_POST['District'])) {
            $model->attributes = $_POST['District'];
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
        $dataProvider = new CActiveDataProvider('District', array(
                    'sort' => array('defaultOrder' => 'state_id ASC')));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new District('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['District']))
            $model->attributes = $_GET['District'];

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

        $model = $_GET['model'];
        $params = array(
            'order' => 'number ASC',
        );

        error_log('called');
        error_log(print_r($_REQUEST, true));


        $data = District::model()->findAllByAttributes(array('state_abbr' => $_POST[$model]['state_abbr']), $params);

        $data = CHtml::listData($data, 'id', 'number');

        foreach ($data as $id => $district) {
            echo $t = CHtml::tag('option', array('value' => $id), CHtml::encode($district), true);
        }
    }

    // print a list of district tag <select>
    public function actionDynamicDistrictNumber() {

        $state_abbr = $_POST['state_abbr'];
        $district_type = $_POST['district_type'];

        $params = array(
            'order' => 'number ASC',
        );

        $data = District::model()->findAllByAttributes(array('state_abbr' => $state_abbr, 'type' => $district_type), $params);

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
