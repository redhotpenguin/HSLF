<?php

class BallotItemController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $category = array('Publishing' => array('/site/publishing/')); // used by the breadcrumb

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
        // import FileUpload helper class
        Yii::import('admin.models.helpers.FileUpload');

        $model = new BallotItem;

        if (isset($_POST['BallotItem'])) {
            $model->attributes = $_POST['BallotItem'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

                $year_month = date('Y_m');
                $destPath = '/' . $year_month; //ex: /2012_06/

                $saved_file_url = $fileUpload->save($_FILES['image_url'], $destPath);

                if ($saved_file_url)
                    $model->image_url = $saved_file_url;
            }

            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, 'updated'=> true));
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
        // import FileUpload helper class
        Yii::import('admin.models.helpers.FileUpload');

        $model = $this->loadModel($id);

        if (isset($_POST['BallotItem'])) {
            $model->attributes = $_POST['BallotItem'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

                $year_month = date('Y_m');
                $destPath = '/' . $year_month; //ex: /2012_06/

                $saved_file_url = $fileUpload->save($_FILES['image_url'], $destPath);

                if ($saved_file_url)
                    $model->image_url = $saved_file_url;
            }

            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, 'updated'=> true));
        }

        $this->render('update', array(
            'model' => $model
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
        $dataProvider = new CActiveDataProvider('BallotItem');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BallotItem('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BallotItem']))
            $model->attributes = $_GET['BallotItem'];

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
        $model = BallotItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ballot-item-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}