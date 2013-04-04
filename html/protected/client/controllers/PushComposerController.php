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
                'actions' => array('index', 'message', 'payload', 'recipient'),
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

    public function actionMessage($direction = 'next') {
        $pushMessageModel = new PushMessage();
        $data = array();
        $response = array();
        $proceedToNextStep = false;


        // message posted
        if (isset($_POST['PushMessage'])) {
            $pushMessageModel->attributes = $_POST['PushMessage'];
            $pushMessageModel->creation_date = date('Y-m-d h:i:s');
            $pushMessageModel->payload_id = 0;

            if ($pushMessageModel->validate()) {
                $proceedToNextStep = true;
                $response['validatedModel'] = array('pushMessage' => $pushMessageModel);
            }
        }

        $data['pushMessageModel'] = $pushMessageModel;

        $response['proceedToNextStep'] = $proceedToNextStep;
        $response['html'] = $this->renderPartial('composer/_message', $data, true);


        $this->printJsonResponse($response);
    }

    public function actionPayload($direction = 'next') {
        if ($direction == 'back') {
            $this->printJsonResponse(array('proceedToLastStep' => true));
        }

        $payloadModel = new Payload();
        $proceedToNextStep = false;

        if (isset($_POST['Payload'])) {
            $payloadModel->attributes = $_POST['Payload'];
            if ($payloadModel->validate()) { // model validated ok. move to next step
                $proceedToNextStep = true;
                $response['validatedModel'] = array('payload' => $payloadModel);
            }
        }

        $data['payloadModel'] = $payloadModel;

        $response['proceedToNextStep'] = $proceedToNextStep;
        $response['html'] = $this->renderPartial('composer/_payload', $data, true);

        $this->printJsonResponse($response);
    }

    public function actionRecipient($direction = 'next') {
        if ($direction == 'back') {
            $this->printJsonResponse(array('proceedToLastStep' => true));
        }


        $proceedToNextStep = false;

        if (isset($_POST['Recipient'])) {
            $proceedToNextStep = true;
        }

        $response = array(
            'html' => $this->renderPartial('composer/_recipient', array(), true),
            'proceedToNextStep' => $proceedToNextStep,
        );


        $this->printJsonResponse($response);
    }

    private function printJsonResponse($data) {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        Yii::app()->end();
    }

}