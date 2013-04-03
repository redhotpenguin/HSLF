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

        if (isset(Yii::app()->session['step' . $virtualSessionId])) {
            $view = $step = Yii::app()->session['step' . $virtualSessionId];
        } else {
            $view = $step = 'message';
        }

        $action = ucfirst($step);

        $methodName = 'handle' . $action . 'Step';

        if (method_exists($this, $methodName)) {
            $this->$methodName($virtualSessionId, $direction, $_POST);
        }
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
        if ($direction === 'back') {
            Yii::app()->session['step' . $virtualSessionId] = 'message';
            return $this->handleMessageStep($virtualSessionId, 'next');
        }

        $view = 'action';
        $payloadModel = new Payload;

        if (isset($payload['Payload'])) {
            $payloadModel->attributes = $payload['Payload'];
            if ($payloadModel->validate()) { // model validated ok. move to next step
                Yii::app()->session['step' . $virtualSessionId] = 'recipient';
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
        if ($direction === 'back') {
            Yii::app()->session['step' . $virtualSessionId] = 'action';
            return $this->handleActionStep($virtualSessionId, 'next');
        }
        $data = array();

        $view = 'recipient';

        logIt($_POST);

        // remove empty tags
        if (isset($payload['tags'])) {
            foreach ($payload['tags'] as $k => $v) {
                if (empty($v)) {
                    unset($payload['tags'][$k]);
                }
            }
        }

        if (isset($payload['tags']) && !empty($payload['tags'])) {
            error_log("got tags");
            Yii::app()->session['step' . $virtualSessionId] = 'confirmation';
            return $this->handleConfirmationStep($virtualSessionId, 'next');
        }

        $this->renderPartial('composer/_' . $view, $data);
    }

    private function handleConfirmationStep($virtualSessionId, $direction, $payload = array()) {
        if ($direction === 'back') {
            Yii::app()->session['step' . $virtualSessionId] = 'recipient';
            return $this->handleRecipientStep($virtualSessionId, 'next');
        }

        $data = array();
        $view = 'confirmation';

        if (isset($_POST['foobar'])) {
            Yii::app()->session['step' . $virtualSessionId] = 'thankyou';
        }

        $this->renderPartial('composer/_' . $view, $data);
    }

    private function handleThankYouStep($virtualSessionId, $direction) {
        $data = array();
        $view = 'thankyou';

        $this->renderPartial('composer/_' . $view, $data);
    }

}