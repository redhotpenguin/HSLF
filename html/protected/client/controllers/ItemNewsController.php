<?php

class ItemNewsController extends Controller {



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
                'actions' => array('index', 'admin', 'view', 'exportCSV'),
                'roles' => array('readBallotItemNews'),
            ),
            array('allow',
                'actions' => array('add'),
                'roles' => array('createBallotItemNews'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateBallotItemNews'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteBallotItemNews'),
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
    public function actionAdd($item_id) {
        if (!is_numeric($item_id))
            return false;

        $item = BallotItem::model()->findByPk($item_id);

        if (!$item)
            return false;


        $model = new ItemNews;
        $model->item_id = $item_id;

        if (isset($_POST['ItemNews'])) {
            $model->attributes = $_POST['ItemNews'];


            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->id, ));
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


        if (isset($_POST['ItemNews'])) {
            $model->attributes = $_POST['ItemNews'];


            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id, ));
        }

        $this->render('editor', array(
            'model' => $model
        ));
    }

    public function loadModel($id) {
        $model = ItemNews::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);

            $item_id = $model->item_id;

            $model->delete();

            // if not ajax edirect to the item update view
            if (!isset($_GET['ajax']))
                $this->redirect(array('ballotItem/update', 'id' => $item_id));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

}