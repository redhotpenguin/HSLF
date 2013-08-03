<?php

class OrganizationController extends CrudController {

    public function __construct() {
        parent::__construct('organization');
        $this->setModel(new Organization);
        $this->setFriendlyModelName('Organization');

        $rules = array(
            array('allow',
                'actions' => array('exportCSV'),
                'roles' => array('readOrganization')
            ),
        );

        $this->setExtraRules($rules);
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        if (isset($postData['Organization']['tags']))
            $model->massUpdateTags($_POST['Organization']['tags']);
        else
            $model->removeAllTagsAssociation();

        if (isset($postData['Organization']['contacts']))
            $model->massUpdateContacts($_POST['Organization']['contacts']);
        else
            $model->removeAllContactsAssociation();
    }

}