<?php

class PushComposerController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array(// restrict State to admins only
                'allow',
                'actions' => array('index', 'composer', 'recipients', 'message', 'action', 'review','thankyou'),
                'roles' => array('manageMobileUsers'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionComposer() {
        $this->render('composer', array("pushMessageModel" => new PushMessage));
    }

    /**
     * render a partial message view
     */
    public function actionMessage() {
        $this->renderPartial('composer/_message');
    }

    public function actionRecipients() {
        $this->renderPartial('composer/_recipients');
    }

    public function actionAction() {
        $this->renderPartial('composer/_action');
    }

    public function actionReview() {
        $this->renderPartial('composer/_review');
    }

    public function actionThankYou() {
        $this->renderPartial('composer/_thankyou');
    }

}