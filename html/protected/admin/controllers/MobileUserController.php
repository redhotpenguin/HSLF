<?php

class MobileUserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $category = array('Application Manager' => array('/site/mobile/')); // used by the breadcrumb

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
                'actions' => array('index', 'browse', 'view', 'delete', 'push', 'getCount'),
                'users' => array('@'),
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

    public function actionGetCount() {

        $attributes = $this->parseFilters($_REQUEST);
        //  echo '<pre>';
        // print_r($attributes);


        $count = MobileUser::model()->find($attributes)->count();

        echo $count;

        die;
    }

    /**
     * Push action - experimental
     */
    public function actionPush() {
        $attributes = $this->parseFilters($_POST);

        print_r($attributes);

        die;
    }

    /**
     * parse filters - experimental
     * return a search array usable by activemongodb
     */
    private function parseFilters($data) {

        foreach ($data as $k => $v) {
            if (empty($v)) {
                unset($data[$k]);
            }
        }

        if (isset($data['tags'])) {
            foreach ($data['tags'] as $k => $v) {
                if (empty($v)) {
                    unset($data['tags'][$k]);
                }
            }
        }

        if (empty($data['tags'])) {
            unset($data['tags']);
        } else {
            // AND TAGS
            $tags = $data['tags'];

            $data['tags'] = array(
                '$all' => $tags
            );
        }

        return $data;
    }

}
