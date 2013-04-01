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
    public function actionNextStep($pageName, $virtualSessionId) {
        $data = array();
        logIt($pageName);
        //   logIt($_POST);

        $data['message'] = Yii::app()->session['message_' . $virtualSessionId];

        switch ($pageName) {
            case 'message':
                $view = 'composer/_message';
                break;

            case 'action':
                // message is stored in $_POST when user click Next Button and is saved in session when user click BACK button.
                if ((!isset($_POST['message']) || empty($_POST['message']) ) && !isset(Yii::app()->session['message_' . $virtualSessionId]))
                    throw new CHttpException(500, "Message missing");


                if (!isset(Yii::app()->session['message_' . $virtualSessionId]))
                    Yii::app()->session['message_' . $virtualSessionId] = $_POST['message'];

                $view = 'composer/_action';
                $data['payloadModel'] = new Payload;

                break;

            case 'recipients':

                $payLoadModel = new Payload();
                $payLoadModel->attributes = $_POST['Payload'];
                if ($payLoadModel->validate()) { // model validated ok. move to next step
                    $view = 'composer/_recipients';
                } else { // validation issue
                    $view = 'composer/_action';
                    $data['payloadModel'] =  $payLoadModel;
                }

                break;

            case 'review':
                $view = 'composer/_review';
                break;

            case 'thankyou':
                $view = 'composer/_thankyou';
                break;
        }

        $this->renderPartial($view, $data);
    }

}