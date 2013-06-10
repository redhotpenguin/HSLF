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
                'actions' => array('index', 'message', 'payload', 'recipient', 'validation', 'confirmation'),
                'roles' => array('manageMobileUsers'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $this->render('index', array('tags' => Tag::model()->findAll()));
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

        $response = array();
        $proceedToNextStep = false;

        if (isset($_POST['TagIds'])) {
            $tagIds = array_unique($_POST['TagIds']);
            $proceedToNextStep = true;
            $response['validatedModel'] = array('tagIds' => $tagIds);
        }

        $response['proceedToNextStep'] = $proceedToNextStep;
        $response['html'] = $this->renderPartial('composer/_recipient', array(), true);

        $this->printJsonResponse($response);
    }

    public function actionValidation($direction = 'next') {
        if ($direction == 'back') {
            $this->printJsonResponse(array('proceedToLastStep' => true));
        }

        $tenant = Yii::app()->user->getLoggedInUserTenant();



        $proceedToNextStep = false;
        if (isset($_POST['Validation']) && isset($_POST['Validation']['PushMessage']) && isset($_POST['Validation']['Payload'])) {

            $unfilteredPushMessage = $_POST['Validation']['PushMessage'];
            $unfilteredPayload = $_POST['Validation']['Payload'];
            $unfilterdTagIds = $_POST['Validation']['TagIds'];

            unset($unfilteredPushMessage['id']); // If id = null. Yii saves the record with id = 0 
            unset($unfilteredPayload['id']);

            $payloadModel = new Payload;
            $payloadModel->attributes = $unfilteredPayload;
            $payloadModel->tenant_id = $tenant->id;


            $pushMessageModel = new PushMessage();
            $pushMessageModel->attributes = $unfilteredPushMessage;
            $pushMessageModel->creation_date = date('Y-m-d h:i:s');
            $pushMessageModel->payload_id = 0; // necessary to pass validation. Needs to be overriden by actual payload_id

            if (!$payloadModel->validate() && !$pushMessageModel->validate()) {
                return false;
            }

            $payloadModel->setIsNewRecord(true);

            if ($payloadModel->save()) {

                $pushMessageModel->payload_id = $payloadModel->id;

                if ($pushMessageModel->save()) {
                    
                    $pushMessageModel->massUpdateTags($unfilterdTagIds); // @WARNING
                    
                    $this->actionConfirmation($pushMessageModel);
                } else {
                    $payloadModel->delete(); // @todo: use transactions to rollback all changes if an error happens
                }
            }

        }

        $response = array(
            'html' => $this->renderPartial('composer/_validation', array(), true),
            'proceedToNextStep' => $proceedToNextStep,
        );


        $this->printJsonResponse($response);
    }

    public function actionConfirmation($pushMessage) {
        $response = array(
            'html' => $this->renderPartial('composer/_confirmation', array('pushMessage' => $pushMessage), true),
            'proceedToNextStep' => false,
        );


        $this->printJsonResponse($response);
    }

    private function printJsonResponse($data) {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        Yii::app()->end();
    }

}