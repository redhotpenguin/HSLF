<?php

Yii::import("backend.vendors.Winningmark.Queues.*", true);

class MobileUserController extends Controller {

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
                'actions' => array('index', 'view', 'delete', 'getCount', 'export'),
                'roles' => array('manageMobileUsers'),
            ),
            array(
                'allow',
                'actions' => array('browse'),
                'roles' => array('admin')
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Index
     */
    public function actionIndex() {
        $this->render('index', array(
            'mobile_user_count' => MobileUser::model()->count(),
            'isAdmin' => Yii::app()->user->hasPermission('admin'),
        ));
    }

    /**
     * Browse all models.
     */
    public function actionBrowse() {
        $model = new MobileUser('search');

        if (isset($_GET['MobileUser'])) {
            $model->fields = $_GET['MobileUser'];
        }

        $this->render('browse', array(
            'model' => $model,
            'mobile_user_count' => MobileUser::model()->count(),
        ));
    }

    /*
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = MobileUser::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Deletes a mobile user
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
     * Print a count of mobile users - ajax 
     */
    public function actionGetCount() {
        $attributes = $this->parseSearchAttributes($_GET);
        $mobileUserModel = MobileUser::model();
        $mobileUserModel->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);
        $count = $mobileUserModel->find($attributes)->count();
        echo $count;
        Yii::app()->end();
    }

    public function actionExport() { // @todo: move logic to a model
        $mobileUserModel = MobileUser::model();

        $tenant = Yii::app()->user->getLoggedInUserTenant();

        if ($tenant == null)
            throw new CHttpException(404, 'The requested page does not exist.');

        $searchAttributes = $this->parseSearchAttributes($_GET);


        $headers = $mobileUserModel->getAttributes();

        $parameters = array(
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->display_name,
            'email' => $tenant->email,
            'mongodb_host' => Yii::app()->params['mongodb_host'],
            'mongodb_name' => Yii::app()->params['mongodb_name'],
            'mongodb_username' => Yii::app()->params['mongodb_user'],
            'mongodb_password' => Yii::app()->params['mongodb_password'],
            'mongodb_collection_name' => 'mobile_user',
            'csvHeaders' => $headers,
            'filterAttributes' => $searchAttributes,
            'requested_by' => Yii::app()->user->name,
        );

        if (Yii::app()->queue->enqueue('mobile_platform', 'MobileUserExportJob', $parameters))
            Yii::app()->user->setFlash('success', "A user export will be sent to {$tenant->email} shortly.");
        else
            Yii::app()->user->setFlash('error', "Error while generating a user export.");

        $this->redirect(array('index'));
    }

    /**
     * parse filters - experimental
     * return a search array usable by activemongodb
     */
    private function parseSearchAttributes($data) {
        $searchConditions = array();

        // retrieve tag names from tag Ids
        if (isset($data['MobileUser']['tags'])) {

            $tagIds = $data['MobileUser']['tags']; // array of tag ids.

            $tags = Yii::app()->db->createCommand()
                    ->select('name, type')
                    ->from('tag')
                    ->where('id IN ( ' . implode(', ', $tagIds) . '  )')
                    ->queryAll();

            $interestTags = array();
            $districtTags = array();
            
            // separate district tags from the rest
            foreach ($tags as $tag) {
                if ($tag['type'] == 'district') {
                    array_push($districtTags, $tag['name']);
                } else {
                    array_push($interestTags, $tag['name']);
                }
            }

            if (count($interestTags) > 0) {
                $searchConditions['tags'] = array(
                    '$in' => $interestTags
                );
            }

            if (count($districtTags) > 0) {
                $searchConditions['districts'] = array(
                    '$in' => $districtTags
                );
            }
        }

        if (isset($data['device_type']) && $data['device_type'] != "") {
            $searchConditions['device_type'] = $data['device_type'];
        }


        if (isset($data['push_only']) && $data['push_only'] === '1') {
            $searchConditions['ua_identifier'] = array('$exists' => true);
        }


        return $searchConditions;
    }

}
