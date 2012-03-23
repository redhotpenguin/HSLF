<?php

Yii::import('zii.widgets.jui.CJuiWidget');

class DistrictTreeView extends CJuiWidget {

    protected $baseUrl;
    public $version='0.1';

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
            $dir =dirname(__FILE__).DIRECTORY_SEPARATOR;
            $this->baseUrl =Yii::app()->getAssetManager()->publish($dir);
    }
 


    public function run(){

        
        
         $this->publishAssets();
         $this->registerClientScripts();
         $ul_id = $this->options['id'];

         $states = State::model()->findAll();
         $finalList = '';


               $state_district = array();
               foreach($states as $state){
                   
                   
                  $finalList.='<div class="state_group">';
                
                     $params = array(
                     'order' => 'number ASC',
                    );
                     
                  $districts = District::model()->findAllByAttributes(array('state_abbr'=> $state->abbr), $params);
                 
                   $finalList.= "<label>  $state->name  </label> ";
                   
                   foreach($districts as $district){
                       $finalList.= '<input type="checkbox" name="district_ids[]" value="'.$district->id.'"> '.$district->number.' <br/>';
                   }
               
                     $finalList.= '</div>';
               }
 
        ?>
       
        <div  style=" ">
                    <?php echo $finalList; ?>
        </div>
       
        <?php
    }

}