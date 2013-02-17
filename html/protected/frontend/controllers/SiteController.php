<?php

class SiteController extends Controller {

    public $layout = '//layouts/main';

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        
      

        switch ($error['code']) {
            case 404:
                $this->render('error404', array('error' => $error));
                break;

            default:
                $this->render('error', array('error' => $error));
                break;
        }
    }

    public function actionPageNotFound() {
        $this->render('page_not_found', 'Page not found!');
    }

}