<?php

Yii::import('zii.widgets.jui.CJuiWidget');

class DistrictTreeView extends CJuiWidget {

    protected $baseUrl;
    public $version = '0.1';

    public function registerClientScripts() {

        if ($this->baseUrl === '')
            throw new CException(Yii::t('JSTree', 'baseUrl must be set.'));
        /*
          $clientScript = Yii::app()->getClientScript();
          $clientScript->registerScriptFile($this->baseUrl.'/jstree/jquery.jstree.js');
          $clientScript->registerScriptFile($this->baseUrl.'/jstree/_lib/jquery.cookie.js');
          $clientScript->registerScriptFile($this->baseUrl.'/jstree/_lib/jquery.hotkeys.js');
          $clientScript->registerCssFile($this->baseUrl.'/jstree/themes/apple/style.css');
         */
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

        $states = State::model()->findAll();
        $finalList = '';


        $state_district = array();
        foreach ($states as $state) {

            $finalList.='<div class="state_group">';

            $params = array(
                'order' => 'number ASC',
            );

            $districts = District::model()->findAllByAttributes(array('state_abbr' => $state->abbr), $params);

            $finalList.= "<label>  $state->name  </label> ";

            foreach ($districts as $district) {
                $finalList.= '<input type="checkbox" name="district_ids[]" value="' . $district->id . '"> ' . $district->number . ' <br/>';
            }

            $finalList.= '</div>';
        }
        ?>



        <?php

    }

    private function getData() {

        $i = 0;
        $states = State::model()->findAll();
        $data = array();
        $params = array('order' => 'number ASC',);

        $state_district = array();
       
        foreach ($states as $state) {
            if($state->abbr == 'na'){
                continue;
            }
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
        
 


        //  return $data;



       /* return $data = array(
            array('id' => 'al',
                'text' => 'Alabama',
                "children" => array(
                    array('id' => 1, 'text' => '<input type="checkbox" name="district_ids[]" value="1" ><label>1</label>'),
                    array('id' => 2, 'text' => 2),
                ),
            ),
        );*/
        
        return $data;
    }

}