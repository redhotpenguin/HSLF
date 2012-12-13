<?php

class ItemController extends Controller {

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


        $model = new Item;

        if (isset($_POST['Item'])) {
            $model->attributes = $_POST['Item'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                if ($this->upload($_FILES))
                    $model->image_url = $saved_file_url;
            }


            $model->save();

            // savescorecards

            $r = $model->validate();


            $save = $model->save();
            if ($save == false) {
                $this->render('create', array(
                    'model' => $model,
                    'endorser_list' => Endorser::model()->findAll(array('order' => 'name')),
                ));
                return;
            }

            // savescorecards
            if ($scorecard_item_ids = getPost('scorecards')) {

                $scorecard_model = new Scorecard();
                foreach ($scorecard_item_ids as $scorecard_item_id => $vote_id) {

                    $scorecard = $scorecard_model->findByAttributes(array(
                        "item_id" => $model->id,
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
                        $scorecard_model->attributes = array('item_id' => $model->id, 'scorecard_item_id' => $scorecard_item_id, 'vote_id' => $vote_id);
                        $scorecard_model->save();
                    }
                }
            }

            $endorser_ids = getPost('endorsers');
            // if any endorsers are selected
            if ($endorser_ids) {
                // remove endorsers that are not selected ( unselected )
                //   $model->removeEndorsersNotIn($endorser_ids);
                // add endorsers
                foreach ($endorser_ids as $endorser_id => $position) {
                    if ($position != 'na') {
                        $model->addEndorser($endorser_id, $position);
                    } else {
                        // @todo: remove endorser if exist
                        $model->removeEndorser($endorser_id);
                    }
                }
            }


            $this->redirect(array('update', 'id' => $model->id, 'updated' => true));
        }

        $model->date_published = date('Y-m-d h:i:s');


        $this->render('create', array(
            'model' => $model,
            'endorser_list' => Endorser::model()->findAll(array('order' => 'name')),
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

        //   logIt($_REQUEST);

        $model = $this->loadModel($id);

        if (!$model->recommendation_id)
            $model->recommendation_id = Recommendation::model()->findByAttributes(array('value' => 'N/A'))->id;

        if (!$model->election_result_id)
            $model->election_result_id = Recommendation::model()->findByAttributes(array('value' => 'N/A'))->id;


        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Item'];

            if (Yii::app()->request->isAjaxRequest) { // if ajax request, perform ajax validation.
                $this->performAjaxValidation($model);
            }

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                if ($this->upload($_FILES))
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

            // save scorecards
            if ($scorecard_item_ids = getPost('scorecards')) {

                $scorecard_model = new Scorecard();
                foreach ($scorecard_item_ids as $scorecard_item_id => $vote_id) {

                    $scorecard = $scorecard_model->findByAttributes(array(
                        "item_id" => $id,
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
                        $scorecard_model->attributes = array('item_id' => $id, 'scorecard_item_id' => $scorecard_item_id, 'vote_id' => $vote_id);
                        $scorecard_model->save();
                    }
                }
            }

            $endorser_ids = getPost('endorsers');
            // if any endorsers are selected
            if ($endorser_ids) {
                // remove endorsers that are not selected ( unselected )
                //   $model->removeEndorsersNotIn($endorser_ids);
                // add endorsers
                foreach ($endorser_ids as $endorser_id => $position) {
                    if ($position != 'na') {
                        $model->addEndorser($endorser_id, $position);
                    } else {
                        // @todo: remove endorser if exist
                        $model->removeEndorser($endorser_id);
                    }
                }
            }

            return;
        }



        $this->render('update', array(
            'model' => $model,
            'endorser_list' => Endorser::model()->findAll(array('order' => 'name')),
        ));
    }

    /**
     * Updates a file
     */
    public function actionUpload() {
        logIt($_FILES);

        if (Yii::app()->request->isPostRequest) {
            if (!empty($_FILES['image_url']['tmp_name'])) {
                echo $t = $this->upload($_FILES);
            }
        }else
            $this->renderPartial('upload');
    }

    /**
     * Call FileUpload to actually save the file
     * Multi Tenant compatible
     */
    private function upload(array $userFile) {

        Yii::import('admin.models.helpers.FileUpload');

        $fileUpload = new FileUpload('image_url', array('image/jpeg', 'image/gif', 'image/png'));

        $year_month = date('Y_m');


        if ($tenantId = Yii::app()->user->tenant_id) {
            $tenant = Tenant::model()->findByPk($tenantId);
            $destPath = '/' . $tenant->name;
        }

        $destPath .= '/' . $year_month; //ex: /2012_06/

        return $fileUpload->save($userFile['image_url'], $destPath);
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
        $dataProvider = new CActiveDataProvider('Item');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Item('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Item']))
            $model->attributes = $_GET['Item'];

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
        $model = Item::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'item-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Performs the CSV Export
     */
    public function actionExportCSV() {
        Yii::import('ext.csv.ESCVExport');

        $csv = new ESCVExport(Item::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('items.csv', $content, "text/csv", false);
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
     * Handle ajax requests for /admin/item/ajax
     */
    public function actionAjax() {
        switch (getParam('a')) {
            // validate an item URL (see Item.js)
            case 'validateURL':
                // get the current record if an ID is provided
                if (getParam('id')) {
                    $item = Item::model()->findByPk(getParam('id'));
                    $validated_url = $item->validateURL(getParam('url'));
                }
                else
                    $validated_url = Item::model()->validateURL(getParam('url'));

                if ($validated_url == false)
                    echo 'invalid_url';
                else
                    echo $validated_url;

                break;


            case 'getScorecardTable':
                if (getParam('id')) {
                    $item = Item::model()->findByPk(getParam('id'));
                    $this->renderPartial('_scorecardTable', array('model' => $item, 'office_id' => getParam('office_id')));
                }else
                    $this->renderPartial('_scorecardTable', array('office_id' => getParam('office_id')));
                break;

            default:
                break;
        }
        exit;
    }

}
