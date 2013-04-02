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
                'actions' => array('index', 'step'),
                'roles' => array('manageMobileUsers'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
// virtual session is to avoid session collision within one actual user session.
// without it, session variables can collide when multiple tabs are open

        $virtualSessionId = md5(microtime(true));
        $this->render('index', array("pushMessageModel" => new PushMessage, 'virtualSessionId' => $virtualSessionId));
    }

    /**
     * Experimental
     * Load next step
     * Validate data
     * => refactor this crap
     * object serialization does not work great with Yii's sessions. Use plain old strings or arrays instead
     */
    public function actionStep($virtualSessionId, $direction = 'next') {

        $data = array();

        if (isset(Yii::app()->session['step' . $virtualSessionId])) {
            $view = $step = Yii::app()->session['step' . $virtualSessionId];
        } else {
            $view = $step = 'message';
        }

        switch ($step) {
            case 'message':
                $data = $this->handleMessageStep($virtualSessionId, $direction, $_POST);
                break;

            case 'action':
                $this->handleActionStep($virtualSessionId, $direction, $_POST);

                break;

            case 'recipients':
                $this->handleRecipientStep($virtualSessionId, $direction, $_POST);

                break;

            case 'confirmation':
                break;

            case 'thankyou':

                break;
        }



        //  $this->renderPartial('composer/_' . $view, $data);
    }

    private function handleMessageStep($virtualSessionId, $direction, $payload = array()) {


        $pushMessageModel = new PushMessage();
        $view = 'message';

// message posted
        if (isset($payload['PushMessage'])) {
            $pushMessageModel->attributes = $payload['PushMessage'];
            $pushMessageModel->creation_date = date('Y-m-d h:i:s');
            $pushMessageModel->payload_id = 0;

            if ($pushMessageModel->validate()) {
                Yii::app()->session['step' . $virtualSessionId] = 'action';
                Yii::app()->session['pushMessage' . $virtualSessionId] = $pushMessageModel->attributes;
                return $this->handleActionStep($virtualSessionId, 'next');
            }
        } elseif (isset(Yii::app()->session['pushMessage' . $virtualSessionId])) { // used by back button. fetched the message from the user session
            $pushMessageModel->attributes = Yii::app()->session['pushMessage' . $virtualSessionId];
        }

        $data = array('pushMessageModel' => $pushMessageModel);

        $this->renderPartial('composer/_' . $view, $data);
    }

    private function handleActionStep($virtualSessionId, $direction, $payload = array()) {
        if ($direction == 'back') {
            Yii::app()->session['step' . $virtualSessionId] = 'message';
            return $this->handleMessageStep($virtualSessionId, 'next');
        }

        $view = 'action';
        $payloadModel = new Payload;

        if (isset($payload['Payload'])) {
            $payloadModel->attributes = $payload['Payload'];
            if ($payloadModel->validate()) { // model validated ok. move to next step
                Yii::app()->session['step' . $virtualSessionId] = 'recipients';
                Yii::app()->session['payload' . $virtualSessionId] = $payloadModel->attributes;

                return $this->handleRecipientStep($virtualSessionId, 'next');
            } else {
                $view = 'action';
            }
        } elseif (isset(Yii::app()->session['payload' . $virtualSessionId])) {
            $payloadModel->attributes = Yii::app()->session['payload' . $virtualSessionId];
        } else {
            $view = 'action';
        }

        $data = array('payloadModel' => $payloadModel);
        $this->renderPartial('composer/_' . $view, $data);
    }

    private function handleRecipientStep($virtualSessionId, $direction, $payload = array()) {
        if ($direction == 'back') {
            Yii::app()->session['step' . $virtualSessionId] = 'action';
            return $this->handleActionStep($virtualSessionId, 'next');
        }


        error_log("handle recipients");
        $data = array();
        $view = 'recipients';

        $this->renderPartial('composer/_' . $view, $data);
    }

}