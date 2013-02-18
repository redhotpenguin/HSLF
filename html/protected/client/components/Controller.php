<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    public function __construct($id) {
        parent::__construct($id);
        if (isset($_GET['tenant_name'])) {
           $r =  Yii::app()->user->setLoggedInUserTenant($_GET['tenant_name']);
           if(!$r){
                 $this->redirect('/client');
           }
        }
    }
    
    public $secondaryNav = array();
}