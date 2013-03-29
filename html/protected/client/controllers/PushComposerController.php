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
                'actions' => array('index', 'composer', 'recipients', 'message', 'action', 'review', 'thankyou'),
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
        
        // virtual session is to avoid session collision within one actual user session.
        // without it, session variables can collide when multiple tabs are open
        
        $virtualSessionId = md5(microtime(true));
        $this->render('composer', array("pushMessageModel" => new PushMessage, 'virtualSessionId'=>$virtualSessionId));
    }

    /**
     * render a partial message view
     */
    public function actionMessage($virtualSessionId) {
        $data = array(
            'message' => Yii::app()->session['message_'.$virtualSessionId],
        );
        $this->renderPartial('composer/_message', $data);
    }

    public function actionRecipients($virtualSessionId, $message) {
        logIt("got:" . $message);
        Yii::app()->session['message_'.$virtualSessionId] = $message;
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