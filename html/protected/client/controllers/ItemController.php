<?php

class ItemController extends Controller {

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
                'actions' => array('index', 'exportCSV', 'exportNewsCSV', 'exportOrganizationCSV',),
                'roles' => array('readBallotItem'),
            ),
            array('allow',
                'actions' => array('create', 'ajax', 'upload'),
                'roles' => array('createBallotItem'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateBallotItem'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteBallotItem'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'edit' page.
     */
    public function actionCreate() {

        //  error_log(print_r($_REQUEST, true));

        $model = new Item;

        if (isset($_POST['Item'])) {
            $model->attributes = $_POST['Item'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                if ($this->upload($_FILES))
                    $model->image_url = $saved_file_url;
            }

            if ($model->save()) {

                // if any organizations are selected
                if (isset($_POST['organizations'] )) {
                    // remove organizations that are not selected ( unselected )
                    //   $model->removeOrganizationsNotIn($organization_ids);
                    // add organizations
                    foreach ($_POST['organizations']  as $organization_id => $position) {
                        if ($position != 'na') {
                            $model->addOrganization($organization_id, $position);
                        } else {
                            // @todo: remove organization if exist
                            $model->removeOrganization($organization_id);
                        }
                    }
                }


                $this->redirect(array('update', 'id' => $model->id, ));
            }
        }

        $model->date_published = date('Y-m-d h:i:s');


        $this->render('editor', array(
            'active_tab' => 'item',
            'model' => $model,
            'organization_list' => Organization::model()->findAll(array('order' => 'name')),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $activeTab = 'item') {

        //   logIt($_REQUEST);

        $model = $this->loadModel($id);

        if (!$model->recommendation_id)
            $model->recommendation_id = Recommendation::model()->findByAttributes(array('value' => 'N/A'))->id;


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
                    $this->redirect(array('update', 'id' => $model->id, ));
            }


            // if any organizations are selected
            if (isset($_POST['organizations'])) {
                // remove organizations that are not selected ( unselected )
                //   $model->removeOrganizationsNotIn($organization_ids);
                // add organizations
                foreach ($_POST['organizations'] as $organization_id => $position) {
                    if ($position != 'na') {
                        $model->addOrganization($organization_id, $position);
                    } else {
                        // @todo: remove organization if exist
                        $model->removeOrganization($organization_id);
                    }
                }
            }

            return;
        }

        $this->render('editor', array(
            'active_tab' => $activeTab,
            'model' => $model,
            'organization_list' => Organization::model()->findAll(array('order' => 'name')),
        ));
    }

    /**
     * Updates a file
     */
    public function actionUpload() {
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
     * If deletion is successful, the browser will be redirected to the index page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

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
        $model = new Item('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Item']))
            $model->attributes = $_GET['Item'];

        $this->render('index', array(
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
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(Item::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('items.csv', $content, "text/csv", false);
    }

    /**
     * Ballot item news to csv
     */
    public function actionExportNewsCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(ItemNews::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('item_news.csv', $content, "text/csv", false);
    }

    /**
     * Ballot item news to csv
     */
    public function actionExportOrganizationCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(ItemOrganization::model()->findAll());


        $content = $csv->toCSV();
        Yii::app()->getRequest()->sendFile('item_organization.csv', $content, "text/csv", false);
    }

    /**
     * Handle ajax requests for /admin/<client>/item/ajax
     */
    public function actionAjax() {
        if (isset($_GET['a']))
            return;

        switch ($_GET['a']) {
            // validate an item URL (see Item.js)
            case 'validateURL':
                // get the current record if an ID is provided
                if (isset($_GET['id'])) {
                    $item = Item::model()->findByPk($_GET['id']);
                    $validated_url = $item->validateURL($_GET['url']);
                }
                else
                    $validated_url = Item::model()->validateURL($_GET['url']);

                if ($validated_url == false)
                    echo 'invalid_url';
                else
                    echo $validated_url;

                break;

            default:
                break;
        }
        exit;
    }

}
