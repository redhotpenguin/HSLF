<?php

class PayloadController extends Controller {

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
            'accessControl', // perform access control for CRUD operations,
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */ public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index'),
                'roles' => array('readPayload'),
            ),
            array('allow',
                'actions' => array('create', 'findPayload'),
                'roles' => array('createPayload'),
            ),
            array('allow',
                'actions' => array('update', 'findPayload'),
                'roles' => array('updatePayload'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deletePayload'),
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
        $model = new Payload;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Payload'])) {
            $model->attributes = $_POST['Payload'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
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

        if (isset($_POST['Payload'])) {
            $model->attributes = $_POST['Payload'];
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via index grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Payload('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Payload']))
            $model->attributes = $_GET['Payload'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionFindPayload($term) {
        $res = array();

        $tenantId = Yii::app()->user->getCurrentTenant()->id;

        if ($term) {

            // ILIKE only works with postgresql
            if (substr(strtolower(Yii::app()->db->connectionString), 0, 5) === 'pgsql')
                $sql = 'SELECT id, title FROM payload where title ILIKE :title AND tenant_id =:tenant_id';
            else
                $sql = 'SELECT id, title FROM payload where title LIKE :title AND tenant_id =:tenant_id';

            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":title", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);
            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Payload the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Payload::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Payload $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'share-payload-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
