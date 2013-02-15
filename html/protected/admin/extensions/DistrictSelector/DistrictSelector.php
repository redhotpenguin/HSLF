<?php

class DistrictSelector extends CInputWidget {

    public $options = array();
    private $state_model;
    private $district;
    private $field_name;
    private $field_id;

    public function init() {
        $this->state_model = State::model();

        if ($this->model->district_id)
            $this->district = District::model()->findByPk($this->model->district_id);
        else
            $this->district = District::model();


        $this->publishAssets();

        list($this->field_name, $this->field_id) = $this->resolveNameID();
    }

    public function run() {

        echo '<div class="controls controls-row">';
        // update model
        if ($this->district->id) {
            $this->StateSelector($this->district->state_id);
            $this->DistrictTypeSelector($this->district->type);
            $this->DistrictNumberSelector($this->district->state_id, ($this->district->type), $this->district->id);
        } else { // new model
            $this->StateSelector();
            $this->DistrictTypeSelector();
            $this->DistrictNumberSelector();
        }

        echo '</div>';
    }

    public static function publishAssets() {
        
    }

    private function StateSelector($selected = null) {
        echo '<div class="span4"> <b>State:</b><br/>';

        $state_list = CHtml::listData($this->state_model->findAll(array('order' => 'name ASC')), 'id', 'name');
        $options = array(
            'tabindex' => '0',
            'empty' => '(not set)',
        );

        $options = array(
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => Yii::app()->params['site_url'] . '/admin/district/dynamicdistrictnumber?model=' . $this->options['model_name'], //url to call.  
                'update' => '#' . $this->field_id, //selector to update      
                ));

        echo CHtml::dropDownList('state_id', $selected, $state_list, $options);
        echo '</div>';
    }

    private function DistrictTypeSelector($selected = null) {
        echo '<div class="span4"> <b>District Type:</b><br/>';

        $district_type_list = $this->district->getTypeOptions();

        //  $url = Yii::app()->params['site_url'] . '/admin/district/dynamicdistrictnumber?model=' . $this->options['model_name'], //url to call.  

        $tenant = Yii::app()->user->getLoggedInUserTenant();

        $url = $url = getSetting('site_url') . '/admin/' . $tenant->name . '/district/dynamicdistrictnumber?model=' . $this->options['model_name'];


        $options = array(
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => $url,
                'update' => '#' . $this->field_id, //selector to update      
                ));



        echo CHtml::dropDownList('district_type', $selected, $district_type_list, $options);
        echo '</div>';
    }

    private function DistrictNumberSelector($state_id = 1, $district_type = '', $selected = null) {
        if ($district_type == '') {
            // get the first value of the $district_types associative array
            reset(District::$district_types);
            $fk = key(District::$district_types);
            $district_type = District::$district_types[$fk];
        }
        echo '<div class="span4"> <b>District:</b><span class="required">*</span><br/>';
        $district_number_list = District::model()->getTagDistrictsByStateAndType($state_id, $district_type);
        echo CHtml::dropDownList($this->field_name, $selected, $district_number_list);
        echo '</div>';
    }

}

?>