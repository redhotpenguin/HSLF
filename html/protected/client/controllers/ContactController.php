<?php

class ContactController extends Controller {

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
                'actions' => array('index', 'exportCSV'),
                'roles' => array('readContact'),
            ),
            array('allow',
                'actions' => array('create', 'findContact'),
                'roles' => array('createContact'),
            ),
            array('allow',
                'actions' => array('update', 'findContact'),
                'roles' => array('updateContact'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteContact'),
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
        $model = new Contact;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Contact'])) {
            $model->attributes = $_POST['Contact'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Contact successfully created");
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

        if (isset($_POST['Contact'])) {
            $model->attributes = $_POST['Contact'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Contact successfully updated");
                $this->redirect(array('update', 'id' => $model->id));
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

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
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
        $model = new Contact('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Contact']))
            $model->attributes = $_GET['Contact'];

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
        $model = Contact::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'contact-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionFindContact($term) {
        $res = array();

        $tenantId = Yii::app()->user->getLoggedInUserTenant()->id;

        if ($term) {

            // ILIKE only works with postgresql
            if (substr(strtolower(Yii::app()->db->connectionString), 0, 5) === 'pgsql')
                $like = 'ILIKE';
            else
                $like = 'LIKE';

            $sql = 'SELECT id, first_name, last_name FROM contact where (first_name ' . $like . ' :name OR last_name ILIKE :name) AND tenant_id =:tenant_id';


            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":name", "%" . $term . "%", PDO::PARAM_STR);
            $cmd->bindValue(":tenant_id", $tenantId, PDO::PARAM_INT);
            $res = $cmd->queryAll();
        }


        echo CJSON::encode($res);
        Yii::app()->end();
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(Contact::model()->findAll());

        $content = $csv->toCSV();

        if ($content == null) {
            Yii::app()->user->setFlash('error', "Nothing to export");
            $this->redirect(array('index'));
        }

        Yii::app()->getRequest()->sendFile('contacts.csv', $content, "text/csv", false);
    }

}