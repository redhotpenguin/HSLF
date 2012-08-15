<?php

class BallotItemController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $category = array('Publishing' => array('/site/publishing/')); // used by the breadcrumb

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'exportCSV', 'upload', 'ajax', 'exportScorecardCSV'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        // import FileUpload helper class
        Yii::import('admin.models.helpers.FileUpload');

        //   error_log(print_r($_REQUEST, true ));


        $model = new BallotItem;

        if (isset($_POST['BallotItem'])) {
            $model->attributes = $_POST['BallotItem'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

                $year_month = date('Y_m');
                $destPath = '/' . $year_month; //ex: /2012_06/

                $saved_file_url = $fileUpload->save($_FILES['image_url'], $destPath);

                if ($saved_file_url)
                    $model->image_url = $saved_file_url;
            }


            $model->save();

            // savescorecards

            $r = $model->validate();


            $save = $model->save();
            if ($save == false) {
                error_log('could not save');
                $this->render('create', array(
                    'model' => $model,
                ));
                return;
            }

            // savescorecards
            if ($scorecard_item_ids = getPost('scorecards')) {

                $scorecard_model = new Scorecard();
                foreach ($scorecard_item_ids as $scorecard_item_id => $vote_id) {

                    $scorecard = $scorecard_model->findByAttributes(array(
                        "ballot_item_id" => $model->id,
                        "scorecard_item_id" => $scorecard_item_id
                            ));

                    if ($scorecard) { // update or delete existing scorecard 
                        if (!$vote_id)
                            $scorecard->delete();


                        $scorecard->vote_id = $vote_id;
                        $scorecard->save();
                    } else { // insert new scorecard
                        if (!$vote_id)
                            continue;

                        $scorecard_model = new Scorecard();
                        $scorecard_model->attributes = array('ballot_item_id' => $model->id, 'scorecard_item_id' => $scorecard_item_id, 'vote_id' => $vote_id);
                        $scorecard_model->save();
                    }
                }
            }



            $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
        }

        $model->date_published = date('Y-m-d h:i:s');


        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        // import FileUpload helper class
        Yii::import('admin.models.helpers.FileUpload');

        $model = $this->loadModel($id);

        if (!$model->recommendation_id)
            $model->recommendation_id = Recommendation::model()->findByAttributes(array('value' => 'N/A'))->id;

        if (!$model->election_result_id)
            $model->election_result_id = Recommendation::model()->findByAttributes(array('value' => 'N/A'))->id;


        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['BallotItem'];

            if (Yii::app()->request->isAjaxRequest) { // if ajax request, perform ajax validation.
                $this->performAjaxValidation($model);
            }



            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

                $year_month = date('Y_m');
                $destPath = '/' . $year_month; //ex: /2012_06/

                $saved_file_url = $fileUpload->save($_FILES['image_url'], $destPath);

                if ($saved_file_url)
                    $model->image_url = $saved_file_url;
            }
            if (Yii::app()->request->isAjaxRequest) { // AJAX Post Request
                if ($model->save()) {
                    echo 'success';
                } else {
                    throw new CException(print_r($model->getErrors(), true));
                }
            } else {  // normal POST request
                if ($model->save())
                    $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
            }
            // savescorecards

            if ($scorecard_item_ids = getPost('scorecards')) {

                $scorecard_model = new Scorecard();
                foreach ($scorecard_item_ids as $scorecard_item_id => $vote_id) {

                    $scorecard = $scorecard_model->findByAttributes(array(
                        "ballot_item_id" => $id,
                        "scorecard_item_id" => $scorecard_item_id
                            ));

                    if ($scorecard) { // update or delete existing scorecard 
                        if (!$vote_id)
                            $scorecard->delete();


                        $scorecard->vote_id = $vote_id;
                        $scorecard->save();
                    } else { // insert new scorecard
                        if (!$vote_id)
                            continue;

                        $scorecard_model = new Scorecard();
                        $scorecard_model->attributes = array('ballot_item_id' => $id, 'scorecard_item_id' => $scorecard_item_id, 'vote_id' => $vote_id);
                        $scorecard_model->save();
                    }
                }
            }


            return;
        }



        $this->render('update', array(
            'model' => $model
        ));
    }

    /**
     * Updates a file
     */
    public function actionUpload() {

        if (Yii::app()->request->isPostRequest) {
            // import FileUpload helper class
            Yii::import('admin.models.helpers.FileUpload');

            if (!empty($_FILES['image_url']['tmp_name'])) {
                $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

                $year_month = date('Y_m');
                $destPath = '/' . $year_month; //ex: /2012_06/

                echo $t = $fileUpload->save($_FILES['image_url'], $destPath);
            }
        }else
            $this->renderPartial('upload');
    }

    /**
     * Deletes a particular model.
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
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('BallotItem');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BallotItem('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BallotItem']))
            $model->attributes = $_GET['BallotItem'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = BallotItem::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ballot-item-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('ext.csv.ESCVExport');

        $csv = new ESCVExport(BallotItem::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('ballot_items.csv', $content, "text/csv", false);
    }

    /**
     * Export scorecards to CSV
     */
    public function actionExportScorecardCSV() {
        Yii::import('ext.csv.ESCVExport');

        $csv = new ESCVExport(Scorecard::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('scorecard.csv', $content, "text/csv", false);
    }

    /**
     * Handle ajax requests for /admin/ballotItem/ajax
     */
    public function actionAjax() {
        switch (getParam('a')) {
            // validate a ballot item URL (see ballotItem.js)
            case 'validateURL':
                // get the current record if an ID is provided
                if (getParam('id')) {
                    $ballot = BallotItem::model()->findByPk(getParam('id'));
                    $validated_url = $ballot->validateURL(getParam('url'));
                }
                else
                    $validated_url = BallotItem::model()->validateURL(getParam('url'));

                if ($validated_url == false)
                    echo 'invalid_url';
                else
                    echo $validated_url;

                break;


            case 'getScorecardTable':
                if (getParam('id')) {
                    $ballot_item = BallotItem::model()->findByPk(getParam('id'));
                    $this->renderPartial('_scorecardTable', array('model' => $ballot_item, 'office_id' => getParam('office_id')));
                }else
                    $this->renderPartial('_scorecardTable', array('office_id' => getParam('office_id')));
                break;

            default:
                break;
        }
        exit;
    }

}
