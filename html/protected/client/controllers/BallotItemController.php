<?php

class BallotItemController extends CrudController {

    public function __construct() {
        parent::__construct('ballotItem');
        $this->setModelName('BallotItem');
        $this->setFriendlyModelName('Ballot Item');


        $rules = array(
            array('allow',
                'actions' => array('exportNewsCSV', 'exportOrganizationCSV'),
                'roles' => array('admin')),
            array('allow',
                'actions' => array('ajax'),
                'roles' => array('createBallotItem'),
            )
        );

        $this->setExtraRules($rules);
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

        // if any organizations are selected
        if (isset($postData['organizations'])) {
            // remove organizations that are not selected ( unselected )
            //   $model->removeOrganizationsNotIn($organization_ids);
            // add organizations
            foreach ($postData['organizations'] as $organization_id => $position) {
                if ($position != 'na') {
                    $model->addOrganization($organization_id, $position);
                } else {
                    // @todo: remove organization if exist
                    $model->removeOrganization($organization_id);
                }
            }
        }
    }

    protected function renderData() {
        return array(
            'active_tab' => 'item',
            'organization_list' => Organization::model()->findAll(array('order' => 'name')),
        );
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
                    $item = BallotItem::model()->findByPk($_GET['id']);
                    $validated_url = $item->validateURL($_GET['url']);
                }
                else
                    $validated_url = BallotItem::model()->validateURL($_GET['url']);

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