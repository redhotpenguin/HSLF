<?php

class TenantController extends CrudController {

    public function __construct() {
        parent::__construct('tenant');
        $this->setModel(new Tenant);
        $this->setFriendlyModelName('Tenant');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Tenant;
        $tenantSetting = new TenantSetting();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Tenant']) && isset($_POST['TenantSetting'])) {
            // save tenant settings first
            $tenantSetting->attributes = $_POST['TenantSetting'];
            $tenantSetting->save();


            $model->attributes = $_POST['Tenant'];
            $model->tenant_setting_id = $tenantSetting->id;
            
            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Tenant successfully created");
                $this->redirect(array('update', 'id' => $model->id,));
            }
        } else {
            $model->creation_date = date('Y-m-d h:i:s');
            $model->api_key = rand(10000, 99999);
            $model->api_secret = md5(rand(10000, 99999));
        }


        $this->render('editor', array(
            'model' => $model,
            'tenantSetting' => new TenantSetting(),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $tenantSetting = $model->getSettingRelation();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Tenant']) && isset($_POST['TenantSetting'])) {
            $model->attributes = $_POST['Tenant'];
            $tenantSetting->attributes = $_POST['TenantSetting'];


            if ($model->save() && $tenantSetting->save())
                $this->redirect(array('update', 'id' => $model->id,));
        }

        $this->render('editor', array(
            'model' => $model,
            'tenantSetting' => $tenantSetting
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Tenant the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Tenant::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Tenant $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tenant-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'create', 'update'),
                'roles' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

}
