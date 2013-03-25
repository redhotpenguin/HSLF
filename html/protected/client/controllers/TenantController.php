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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Tenant'])) {
            $model->attributes = $_POST['Tenant'];
            if ($model->save())
                Yii::app()->user->setFlash('success', "Tenant successfully created");

            $this->redirect(array('update', 'id' => $model->id,));
        }else {
            $model->creation_date = date('Y-m-d h:i:s');
            $model->api_key = rand(10000, 99999);
            $model->api_secret = md5(rand(10000, 99999));
        }


        $this->render('editor', array(
            'model' => $model,
        ));
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
