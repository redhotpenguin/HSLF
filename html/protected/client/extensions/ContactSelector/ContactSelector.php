<?php

class ContactSelector extends CInputWidget {

    public $options = array();

    protected static function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/contact.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/contact.css', CClientScript::POS_HEAD);
        }
    }

    public function init() {
        $this->publishAssets();
    }

    public function run() {
        $contacts = $this->model->contacts;
        $this->render('contact_selector', array(
            'contacts' => $contacts,
            'model' => $this->model,
            'helpText' => isset($this->options['help_text']) ? $this->options['help_text'] : null,
            'dropDownText' => isset($this->options['dropdown_text']) ? $this->options['dropdown_text'] : null,
                )
        );
    }

}