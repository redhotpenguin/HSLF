<?php

class UserController extends Controller {

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
                'actions' => array('permissions', 'updateTasks'),
                'roles' => array('admin'),
            ),
            array('allow',
                'actions' => array('index',),
                'roles' => array('readUser'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createUser'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateUser'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteUser'),
            ),
            array('allow', // allow every authenticated users to update their login info
                'actions' => array('settings'),
                'users' => array('@'),
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
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {

            $model->attributes = $_POST['User'];
            //  $model->password = sha1($_POST['User']['password']);

            if ($model->save()) {

                // @todo: refactor this
                if (isset($_POST['add_to_tenant']) && !empty($_POST['add_to_tenant'])) {
                    $tenantName = $_POST['add_to_tenant'];


                    $tenant = Tenant::model()->findByAttributes(array("name" => $tenantName));
                    if ($tenant) {
                        if (!$model->addToTenant($tenant->id)) {
                            Yii::app()->user->setFlash('error', "Error adding the user to this project");
                            $updatedResult = false;
                        }
                    } else {
                        Yii::app()->user->setFlash('error', "This tenant account does not exist");
                        $updatedResult = false;
                    }
                }

                $this->redirect(array('update', 'id' => $model->id, 'created' => true));
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

        // hold the current password ( before it might gets updated)
        $current_password = $model->password;

        if (isset($_POST['User'])) {


            $model->attributes = $_POST['User'];

            // if a new password has been given
            if ($model->password)
                $model->initial_password = $model->password;

            else
                $model->initial_password = $current_password;

            if ($model->save())
                $updatedResult = true;

            // @todo: refactor this.. crap
            if (isset($_POST['add_to_tenant']) && !empty($_POST['add_to_tenant'])) {
                $tenantName = $_POST['add_to_tenant'];


                $tenant = Tenant::model()->findByAttributes(array("name" => $tenantName));
                if ($tenant) {
                    if (!$model->addToTenant($tenant->id)) {
                        Yii::app()->user->setFlash('error', "Error adding the user to this project");
                        $updatedResult = false;
                    }
                } else {
                    Yii::app()->user->setFlash('error', "This tenant account does not exist");
                    $updatedResult = false;
                }
            }

            // @todo: refactor this
            if (isset($_POST['remove_from_tenant']) && !empty($_POST['remove_from_tenant'])) {
                $tenantName = $_POST['remove_from_tenant'];


                $tenant = Tenant::model()->findByAttributes(array("name" => $tenantName));
                if ($tenant) {
                    if (!$model->removeFromTenant($tenant->id)) {
                        Yii::app()->user->setFlash('error', "Error removing the user to this project");
                        $updatedResult = false;
                    }
                } else {
                    Yii::app()->user->setFlash('error', "This tenant account does not exist");
                    $updatedResult = false;
                }
            }



            $this->redirect(
                    array('update',
                        'id' => $model->id,
                        'updated' => $updatedResult
            ));
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
            $user = $this->loadModel($id);

            if ($user->username == 'admin') {
                throw new CHttpException(403, 'Deleting the admin account is forbidden.');
            }

            $user->delete();

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
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('index', array(
            'model' => $model,
            'role' => 'publisher'
        ));
    }

    /**
     * Update a logged in user credentials
     */
    public function actionSettings() {
        $model = User::model()->findByPk(Yii::app()->user->id);
        $model->scenario = "update";
        $currentPassword = $model->password;
        $currentRole = $model->getRole();


        if (isset($_POST['User'])) {

            // some user attributes can't be updated using mass assignment/
            // See User model rules
            $model->attributes = $_POST['User'];

            // except for role
            $model->role = $currentRole;

            // if a new password has been given
            if ($model->password)
                $model->initial_password = $model->password;
            else
                $model->initial_password = $currentPassword;

            if ($model->save())
                $this->redirect(array('settings', 'updated' => true));
        }


        $this->render('my_account', array('model' => $model));
    }

    /**
     * display permission page for a user (specific to tenant)
     * @param integer $tenantId tenant id
     * @param integer $userId user id
     */
    public function actionPermissions($tenantId, $userId) {
        $user = $this->loadModel($userId);

        $tasks = Yii::app()->authManager->getTasks();
        $assignedTasks = Yii::app()->authManager->getTasks("$tenantId,$userId"); // @todo: update this
        $this->render('permissions', array(
            'user' => $user,
            'tenantId' => $tenantId,
            'tasks' => $tasks,
            'assignedTasks' => $assignedTasks
        ));
    }

    public function actionUpdateTasks() {
        $tasks = array();

        if (isset($_POST['tasks']))
            $tasks = $_POST['tasks'];

        if (!isset($_POST['tenantId']) || !isset($_POST['userId']) || !is_numeric($_POST['tenantId']) || !is_numeric($_POST['userId'])) {
            throw new CHttpException(500, "Please do not repeat this request again.");
        }


        $tenantId = $_POST['tenantId'];

        $user = $this->loadModel($_POST['userId']);


        if ($user->updateTasks($tenantId, $tasks))
            $result = 'success';
        else
            $result = 'error';


        $this->redirect(array('permissions',
            'tenantId' => $tenantId,
            'userId' => $user->id,
            'result' => $result
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'User not found.');

        $model->afterLoadModel();

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
