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

        if (isset($_POST['User'])) {

            $model->attributes = $_POST['User'];

            if ($model->save()) {

                if (( isset($_POST['add_to_tenant']) && !empty($_POST['add_to_tenant'])))
                    if (!$this->addUserToTenant($model, $_POST['add_to_tenant']))
                        Yii::app()->user->setFlash('error', "Error while adding this user to this tenant");

                if (isset($_POST['administrator']) && $_POST['administrator'] == '1') {
                    Yii::app()->authManager->assign('admin', $model->getTenantUserId(0));
                }

                Yii::app()->user->setFlash('success', "User successfully updated.");

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

        // hold the current password ( before it might gets updated)
        $current_password = $model->password;

        if (isset($_POST['User'])) {
            $updatedResult = 'false';

            $model->attributes = $_POST['User'];

            // if a new password has been given
            if ($model->password)
                $model->initial_password = $model->password;

            else
                $model->initial_password = $current_password;

            if ($model->save())
                $updatedResult = true;

            if (( isset($_POST['add_to_tenant']) && !empty($_POST['add_to_tenant'])))
                if (!$this->addUserToTenant($model, $_POST['add_to_tenant']))
                    Yii::app()->user->setFlash('error', "Error while adding {$model->username} to {$_POST['add_to_tenant']}");


            if (isset($_POST['remove_from_tenant']) && !empty($_POST['remove_from_tenant']))
                if (!$this->removeUserFromTenant($model, $_POST['remove_from_tenant']))
                    Yii::app()->user->setFlash('error', "Error while removing {$model->username} from {$_POST['remove_from_tenant']}");

            try {
                if (isset($_POST['administrator']) && $_POST['administrator'] == '1') {
                    Yii::app()->authManager->assign('admin', $model->getTenantUserId(0));
                } else {
                    Yii::app()->authManager->revoke('admin', $model->getTenantUserId(0));
                }
            } catch (CDbException $e) {
                error_log("error updating admin privileges for user $model->id");
            }
            Yii::app()->user->setFlash('success', "User successfully updated.");

            $this->redirect(
                    array('update',
                        'id' => $model->id,
            ));
        }

        $this->render('editor', array(
            'model' => $model,
        ));
    }

    private function addUserToTenant($user, $tenantName) {
        $tenant = Tenant::model()->findByAttributes(array("name" => $tenantName));

        if ($tenant)
            if ($user->addToTenant($tenant->id))
                return true;

        return false;
    }

    private function removeUserFromTenant($user, $tenantName) {
        $tenant = Tenant::model()->findByAttributes(array("name" => $tenantName));
        if ($tenant)
            if ($user->removeFromTenant($tenant->id))
                return true;

        return false;
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
        $model->scenario = "updateSettings";
        $currentPassword = $model->password;


        if (isset($_POST['User'])) {

            // some user attributes can't be updated using mass assignment/
            // See User model rules
            $model->attributes = $_POST['User'];

            // if a new password has been given
            if ($model->password)
                $model->initial_password = $model->password;
            else
                $model->initial_password = $currentPassword;

            if ($model->save()) {
                Yii::app()->user->setFlash('account_settings_success', "User successfully updated");
                $this->redirect(array('settings'));
            } else {
                logIt($model->errors);
                Yii::app()->user->setFlash('account_settings_error', 'Could not save user');
            }
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
        if (($tenant = Tenant::model()->findByPk($tenantId)) == null)
            throw new CHttpException(404, "Tenant not found");

        $publisherTasks = Yii::app()->authManager->getItemChildren('publisher');

        $tenantUserId = $user->getTenantUserId($tenant->id, $userId);

        $assignedTasks = Yii::app()->authManager->getTasks($tenantUserId);

        $unassignedTasks = array_diff_key($publisherTasks, $assignedTasks);

        foreach ($assignedTasks as $task)
            $list[$task->name] = array('description' => $task->description, 'checked' => true);


        foreach ($unassignedTasks as $task)
            $list[$task->name] = array('description' => $task->description, 'checked' => false);


        asort($list);

        $projectAdministrator = Yii::app()->authManager->checkAccess('admin', $tenantUserId);


        $this->render('permissions', array(
            'user' => $user,
            'tenant' => $tenant,
            'taskList' => $list,
            'projectAdministrator' => $projectAdministrator,
        ));
    }

    /**
     * handle user task updates
     */
    public function actionUpdateTasks() {
        $tasks = array();


        if (isset($_POST['tasks']))
            $tasks = $_POST['tasks'];

        if (!isset($_POST['tenantId']) || !isset($_POST['userId']) || !is_numeric($_POST['tenantId']) || !is_numeric($_POST['userId'])) {
            throw new CHttpException(500, "Please do not try this again.");
        }

        $tenantId = $_POST['tenantId'];

        $user = $this->loadModel($_POST['userId']);


        // make sure that the tasks received actually belongs to the permission role and not something else (ex: admin role)
        $publisherTasks = Yii::app()->authManager->getItemChildren('publisher');

        foreach ($tasks as $task)
            if (!isset($publisherTasks[$task]))
                throw new CHttpException(500, 'Please do not try this again');

        $tenantUserId = $user->getTenantUserId($_POST['tenantId'], $_POST['userId']);


        if ($user->updateTasks($tenantId, $tasks))
            Yii::app()->user->setFlash('success', "User permissions successfully updated.");
        else
            Yii::app()->user->setFlash('error', "Error while updating user permissions");

        // allow users to be an admin of a specific project
        // needs to happen after 'updateTasks' because 'updateTasks' revoke all permissions then add the new ones back
        if (isset($_POST['projectAdministrator']) && $_POST['projectAdministrator'] == '1') {

            Yii::app()->authManager->assign('admin', $tenantUserId);
        } else {
            Yii::app()->authManager->revoke('admin', $tenantUserId);
        }


        $this->redirect(array('permissions',
            'tenantId' => $tenantId,
            'userId' => $user->id
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
