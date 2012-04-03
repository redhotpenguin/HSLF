<?php

Yii::import('zii.widgets.jui.CJuiWidget');

class DistrictTreeView extends CJuiWidget {

    protected $baseUrl;
    public $version = '0.1';

    public function registerClientScripts() {

        if ($this->baseUrl === '')
            throw new CException(Yii::t('JSTree', 'baseUrl must be set.'));
    }

    public function publishAssets() {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
    }

    public function run() {


        $data = $this->getData();

        $this->widget('CTreeView', array(
            'data' => $data,
            'animated' => 'normal',
            'collapsed'=>true,
            'htmlOptions' => array('class' => 'treeview-gray')));


        $this->publishAssets();
        $this->registerClientScripts();
        $ul_id = $this->options['id'];

        $states = State::model()->findAll(array('order' => 'abbr ASC') );
        $finalList = '';


        $state_district = array();
   
       

    }

    private function getData() {
        $i = 0;
        $states = State::model()->findAll(array('order' => 'abbr ASC'));
        $data = array();
        $params = array('order' => 'number ASC');

        $state_district = array();
       
        foreach ($states as $state) {
             $row = array();
             $row['id'] = $state->abbr;
             $row['text'] = $state->name;

            $districts = District::model()->findAllByAttributes(array('state_abbr' => $state->abbr), $params);

            $data['children'] = array();
            $children = array();
            
            if(count($districts) == 0) 
                continue;
            foreach ($districts as $district) {
                $child = array();
              
                $child['id']= $district->id;
                $child['text']= '<input type="checkbox" name="district_ids[]" value="' . $district->id . '"> <span class="district_number"> '.$district->number.'</span>';
                
                array_push($children, $child);
            }

        
               $row['children'] = $children;

               array_push($data, $row);
        }

        
        return $data;
    }

}