<?php

/**
 * Generic Controller
 * Perform basic Create Read Update Delete operations on a given model
 */
abstract class CrudController extends Controller {

    private $extraRules = array();
    private $model;
    private $modelName;
    private $friendlyModelName;

    /**
     * Set the model linked to the controller
     * @param $model CActiveRecord model
     */
    protected function setModel(CActiveRecord $model) {
        $this->model = $model;
        $this->modelName = get_class($model);
    }

    /**
     * return the model linked to the controller
     * @return CActiveRecord model
     */
    protected function getModel() {
        return $this->model;
    }

    /**
     * Set a friendly name for the linked model
     * @param string $name friendly name
     */
    protected function setFriendlyModelName($name) {
        $this->friendlyModelName = $name;
    }

    /**
     * Add extra rules that are not covered by the default CRUD rules
     * @param array $rules
     */
    protected function setExtraRules(array $rules) {
        $this->extraRules = $rules;
    }

    /**
     * Return the extra rules that are not covered by the default CRUD rules
     * @return array array of rules
     */
    protected function getExtraRules() {
        return $this->extraRules;
    }

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

        $rules = array(
            array('allow',
                'actions' => array('index'),
                'roles' => array('read' . $this->modelName),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('create' . $this->modelName),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('update' . $this->modelName),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('delete' . $this->modelName),
            ),
            array(
                'allow',
                'actions' => array('exportCSV'),
                'roles' => array('admin')
            ),
        );

        $rules = array_merge($rules, $this->getExtraRules());

        array_push($rules, array('deny', // deny all users
            'users' => array('*'),
        ));

        return $rules;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $model = $this->getModel();

        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];

            if ($model->save()) {

                if (method_exists($this, 'afterSave'))
                    $this->afterSave($model, $_POST);

                if (Yii::app()->request->isAjaxRequest) { // AJAX Post Request
                    header('Content-type: application/json');
                    echo CJSON::encode($model);
                    Yii::app()->end();
                }

                Yii::app()->user->setFlash('success', "{$this->friendlyModelName}  successfully created");
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $data = array(
            'model' => $model,
        );


        if (method_exists($this, 'renderData'))
            $data = array_merge($data, $this->renderData());

        $this->render('editor', $data);
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

        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];
            if ($model->save()) {

                if (method_exists($this, 'afterSave'))
                    $this->afterSave($model, $_POST);

                if (Yii::app()->request->isAjaxRequest) { // AJAX Post Request
                    echo 'success';
                    Yii::app()->end();
                }

                Yii::app()->user->setFlash('success', "{$this->friendlyModelName}  successfully updated");

                $this->redirect(array('update', 'id' => $model->id));
            }
        }


        $data = array(
            'model' => $model,
        );

        if (method_exists($this, 'renderData'))
            $data = array_merge($data, $this->renderData());

        $this->render('editor', $data);
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
        $model = new $this->modelName('search');
        $model->unsetAttributes();  // clear any default values


        if (isset($_GET[$this->modelName])) {
            $model->attributes = $_GET[$this->modelName];
        }

        $this->render('index', array(
            'model' => $model,
            'isAdmin' => Yii::app()->user->hasPermission('admin'),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = new $this->modelName;

        $model = $model->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        $ajaxFormName = strtolower($this->modelName) . '-form';

        if (isset($_POST['ajax']) && $_POST['ajax'] === $ajaxFormName) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $model = new $this->modelName;

        $csv = new ESCVExport($model->findAll());

        $content = $csv->toCSV();

        if ($content == null) {
            Yii::app()->user->setFlash('error', "Nothing to export");
            $this->redirect(array('index'));
        }

        Yii::app()->getRequest()->sendFile($this->friendlyModelName . '.csv', $content, "text/csv", false);
    }

}