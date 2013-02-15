<?php

Yii::import("admin.vendors.Winningmark.Queues.*", true);

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
                'actions' => array('index', 'browse', 'view', 'delete', 'getCount', 'sendAlert', 'export'),
                'roles' => array('manageMobileUsers'),
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
        $attributes = $this->parseSearchAttributes($_GET); // @todo: filter $_GET

        $mobileUserModel = MobileUser::model();
        $mobileUserModel->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);
        $count = $mobileUserModel->find($attributes)->count();
        echo $count;
        die;
    }

    /**
     * Push an alert to a worker
     */
    public function actionSendAlert() {

        $extra = array();

        if (!isset($_POST['alert']) || empty($_POST['alert'])) {
            echo 'Alert missing.';
            die;
        }


        $searchAttributes = $this->parseSearchAttributes($_POST);

        $searchAttributes['ua_identifier'] = array('$exists' => true); // make sure we only select users with a ua identifier

        $alert = $_POST['alert']; //@todo: filter  + check length
        // parse key value (extra)
        if (isset($_POST['keys']) && isset($_POST['values'])) {
            $keys = $_POST['keys'];
            $values = $_POST['values'];


            if (count($keys) == count($values)) {

                $i = 0;

                foreach ($keys as $key) {
                    $extra[$key] = $values[$i];
                    $i++;
                }
            }
        }

        $tenant = Yii::app()->user->getLoggedInUserTenant();
        
        try {
            $jobProducer = new UAJobProducer($tenant);
            $jobResult = $jobProducer->pushUrbanAirshipMessage($alert, $searchAttributes, $extra);
        } catch (JobProducerException $e) {
            $jobResult = $e->getMessage();
        }

        echo ($jobResult === true ? "success" : $jobResult);

        die;
    }

    public function actionExport() { // @todo: move logic to a model
        $searchAttributes = $this->parseSearchAttributes($_GET);

        $fp = fopen('php://temp', 'w');

        $mobileUserModel = MobileUser::model();
        $mobileUserModel->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);

        $headers = $mobileUserModel->getAttributes();

        fputcsv($fp, $headers);

        $mobileUserCursor = $mobileUserModel->find($searchAttributes);

        foreach ($mobileUserCursor as $mobileUser) {
            $row = array();
            foreach ($headers as $head => $friendlyHeadName) {
                $data = null;

                if (isset($mobileUser[$head])) {
                    if (is_array($mobileUser[$head])) {
                        $data = implode(', ', $mobileUser[$head]);
                    } elseif ($mobileUser[$head] instanceof MongoDate) {
                        $data = date('m-d-Y h:i:s', $mobileUser[$head]->sec);
                    } else {
                        $data = $mobileUser[$head];
                    }
                }

                $row[] = $data;
            }
            fputcsv($fp, $row);
        }

        rewind($fp);
        $content = stream_get_contents($fp);

        fclose($fp);

        Yii::app()->getRequest()->sendFile('mobileUsers.csv', $content, "text/csv", false);
    }

    /**
     * parse filters - experimental
     * return a search array usable by activemongodb
     */
    private function parseSearchAttributes($data) {

        // allowed search attributes @todo: move to MobileUser Model
        $searchAttributes = array(
            "tags", "device_type", "push_only", "districts"
        );

        // strip out key/values that are not in $searchAttributes
        // remove keys with empty values
        foreach ($data as $k => $v) {
            if (!in_array($k, $searchAttributes)) {
                unset($data[$k]);
                continue;
            }

            if (empty($v)) {
                unset($data[$k]);
            }
        }

        // remove empty tags
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $k => $v) {
                if (empty($v)) {
                    unset($data['tags'][$k]);
                }
            }
        }

        // remove empty districts
        if (isset($data['districts'])) {
            foreach ($data['districts'] as $k => $v) {
                if (empty($v)) {
                    unset($data['districts'][$k]);
                }
            }
        }

        if (empty($data['tags'])) {
            unset($data['tags']);
        } else {
            // OR TAGS
            $tags = array_values($data['tags']); // reindex tags (otherwise mongodb driver fail when using $all)
            $data['tags'] = array(
                '$in' => $tags
            );
        }


        if (empty($data['districts'])) {
            unset($data['districts']);
        } else {
            // OR Districts 
            $districts = array_values($data['districts']); // reindex tags (otherwise mongodb driver fail when using $all)
            $data['districts'] = array(
                '$in' => $districts
            );
        }

        if (isset($data['push_only']) && $data['push_only'] === '1') {
            unset($data['push_only']);
            $data['ua_identifier'] = array('$exists' => true);
        }

        return $data;
    }

}
