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
                'actions' => array('index', 'nextStep'),
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
     */
    public function actionNextStep($virtualSessionId) {
        $data = array();
        logIt($_POST);

        $data['message'] = Yii::app()->session['message_' . $virtualSessionId];


        if (isset(Yii::app()->session['step' . $virtualSessionId])) {
            $step = Yii::app()->session['step' . $virtualSessionId];
        } else {
            $step = 'message';
        }


        switch ($step) {
            case 'message':
                $view = 'composer/_message';

                $pushMessageModel = new PushMessage();

                $data['pushMessageModel'] = $pushMessageModel;
                Yii::app()->session['step' . $virtualSessionId] = 'action';
                break;

            case 'action':

                $pushMessageModel = new PushMessage;

                $pushMessageModel->attributes = $_POST['PushMessage'];
                $pushMessageModel->creation_date = date('Y-m-d h:i:s');
                $pushMessageModel->payload_id = 0;

                if ($pushMessageModel->validate()) {
                    $view = 'composer/_action';
                    $data['payloadModel'] = new Payload;
                    Yii::app()->session['step' . $virtualSessionId] = 'recipients';
                } else { // validation issue
                    $view = 'composer/_message';
                    $data['pushMessageModel'] = $pushMessageModel;
                    Yii::app()->session['step' . $virtualSessionId] = 'action';
                }

                Yii::app()->session['step' . $virtualSessionId] = 'recipients';

                break;

            case 'recipients':

                $payLoadModel = new Payload();
                $payLoadModel->attributes = $_POST['Payload'];
                if ($payLoadModel->validate()) { // model validated ok. move to next step
                    $view = 'composer/_recipients';
                    Yii::app()->session['step' . $virtualSessionId] = 'review';
                } else { // validation issue
                    $view = 'composer/_action';
                    $data['payloadModel'] = $payLoadModel;
                    Yii::app()->session['step' . $virtualSessionId] = 'recipients';
                }

                break;

            case 'review':
                $view = 'composer/_review';
                Yii::app()->session['step' . $virtualSessionId] = 'thankyou';
                break;

            case 'thankyou':
                $view = 'composer/_thankyou';
                break;
        }

        $this->renderPartial($view, $data);
    }

}