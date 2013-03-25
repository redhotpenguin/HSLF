<?php

class ScorecardItemController extends Controller {

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
                'roles' => array('readScorecardItem'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createScorecardItem'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateScorecardItem'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteScorecardItem'),
            ),
            array(
                'allow',
                'actions' => array('exportCSV'),
                'roles' => array('admin')
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
        $model = new ScorecardItem;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ScorecardItem'])) {
            $model->attributes = $_POST['ScorecardItem'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Scorecard Item successfully created");
                $this->redirect(array('update', 'id' => $model->id));
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

        if (isset($_POST['ScorecardItem'])) {
            $model->attributes = $_POST['ScorecardItem'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Scorecard Item successfully updated");

                $this->redirect(array('update', 'id' => $model->id));
            } else {
                echo 'could not save';
                exit;
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
        $model = new ScorecardItem('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ScorecardItem']))
            $model->attributes = $_GET['ScorecardItem'];

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
        $model = ScorecardItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'scorecard-item-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(ScorecardItem::model()->findAll());

        $content = $csv->toCSV();

        if ($content == null) {
            Yii::app()->user->setFlash('error', "Nothing to export");
            $this->redirect(array('index'));
        }


        Yii::app()->getRequest()->sendFile('scorecard_item.csv', $content, "text/csv", false);
    }

}
