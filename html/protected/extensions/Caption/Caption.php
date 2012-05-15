<?php

//echo __FILE__;

require_once(dirname(__FILE__) . '/language/caption_dictionary.php');

class Caption extends CWidget {

    public function init() {
        $this->publishAssets();
    }

    public function run() {

        $controller_name = Yii::app()->controller->id;
        $controller_action = Yii::app()->controller->getAction()->getId();

        $this->render('caption', array(
            'caption_name' => $controller_name,
            'caption_data' => $this->getControllerCaption($controller_name, $controller_action),
        ));
    }

    private function getControllerCaption($controller_name, $action = null) {
        global $caption_dictonary;

        if (array_key_exists($controller_name, $caption_dictonary))
            return array(
                $caption_dictonary[$controller_name]['description'],
                $caption_dictonary[$controller_name]['action'][$action],
            );
        else
            return false;
    }
    
    public static function publishAssets(){
       	$assets=dirname(__FILE__).'/assets';
		$baseUrl=Yii::app()->assetManager->publish($assets);
		if(is_dir($assets)){
			Yii::app()->clientScript->registerCssFile($baseUrl.'/caption.css');
		} else {
			throw new Exception('EClEditor - Error: Couldn\'t find assets to publish.');
		}
    }
}