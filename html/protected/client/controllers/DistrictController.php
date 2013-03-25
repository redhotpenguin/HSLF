<?php

class DistrictController extends CrudController {

    public function __construct() {
        parent::__construct('district');
        $this->setModelName('District');
        $this->setFriendlyModelName('District');

        $this->setExtraRules(
                array(array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions' => array('dynamicdistrictnumber', 'dynamicdistrict'),
                        'users' => array('@'))));
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

    // print a list of district tag <select>
    public function actionDynamicDistrictNumber() {

        if (!isset($_POST['state_id']) || !isset($_POST['district_type']))
            return;

        $state_id = $_POST['state_id'];
        $district_type = $_POST['district_type'];

        $params = array(
            'order' => 'number ASC',
        );

        $data = District::model()->findAllByAttributes(array('state_id' => $state_id, 'type' => $district_type), $params);

        $data = CHtml::listData($data, 'id', 'display_name');
        asort($data);

        foreach ($data as $id => $district) {
            if (empty($district))
                $district = 'N/A';

            echo CHtml::tag('option', array('value' => $id), CHtml::encode($district), true);
        }
    }

}