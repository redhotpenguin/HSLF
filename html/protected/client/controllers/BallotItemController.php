<?php

class BallotItemController extends CrudController {

    public function __construct() {
        parent::__construct('ballotItem');
        $this->setModelName('BallotItem');
        $this->setFriendlyModelName('Ballot Item');

        $this->setExtraRules(array(
            'allow',
            'actions' => array('exportNewsCSV', 'exportOrganizationCSV'),
            'roles' => array('admin')
        ));
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'edit' page.
     */
    public function actionCreate() {

        //  error_log(print_r($_REQUEST, true));

        $model = new BallotItem;

        if (isset($_POST['BallotItem'])) {
            $model->attributes = $_POST['BallotItem'];

            // a file for image_url has been uploded
            if (!empty($_FILES['image_url']['tmp_name'])) {
                if ($this->upload($_FILES))
                    $model->image_url = $saved_file_url;
            }

            if ($model->save()) {

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


                Yii::app()->user->setFlash('success', "Ballot item successfully created");

                $this->redirect(array('update', 'id' => $model->id,));
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
            $model->attributes = $_POST['BallotItem'];

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
                    $this->redirect(array('update', 'id' => $model->id,));
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
     * Ballot item news to csv
     */
    public function actionExportNewsCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(ItemNews::model()->findAll());

        $content = $csv->toCSV();

        if ($content == null) {
            Yii::app()->user->setFlash('error', "Nothing to export");
            $this->redirect(array('index'));
        }

        Yii::app()->getRequest()->sendFile('item_news.csv', $content, "text/csv", false);
    }

    /**
     * Ballot item news to csv
     */
    public function actionExportOrganizationCSV() {
        Yii::import('backend.extensions.csv.ESCVExport');

        $csv = new ESCVExport(ItemOrganization::model()->findAll());

        $content = $csv->toCSV();

        if ($content == null) {
            Yii::app()->user->setFlash('error', "Nothing to export");
            $this->redirect(array('index'));
        }

        Yii::app()->getRequest()->sendFile('item_organization.csv', $content, "text/csv", false);
    }

    /**
     * Updates a file
     */
    public function actionUpload() {
        if (Yii::app()->request->isPostRequest) {
            if (!empty($_FILES['image_url']['tmp_name'])) {
                echo $t = $this->upload($_FILES);
            }
        } else
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
     * Handle ajax requests for /admin/<client>/item/ajax
     */
    public function actionAjax() {

        if (!isset($_GET['a']))
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