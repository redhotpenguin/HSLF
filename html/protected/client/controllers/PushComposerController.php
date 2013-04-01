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
                'actions' => array('index', 'composer', 'nextStep'),
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
        $this->render('composer', array("pushMessageModel" => new PushMessage, 'virtualSessionId' => $virtualSessionId));
    }

    public function actionNextStep($pageName, $virtualSessionId) {
        $data = array();

      //  logIt($_POST);
        
        $message = 'debug';
usleep(200000);
        $data['message'] = Yii::app()->session['message_' . $virtualSessionId];

        switch ($pageName) {
            case 'message':
                $view = 'composer/_message';
                break;

            case 'action':
                if(!isset($_POST['message']) || empty($_POST['message']))
                    throw new CHttpException(500, "message missing");
                
                logIt("mess: ".$_POST['message']);
                Yii::app()->session['message_' . $virtualSessionId] = $_POST['message'];
                $view = 'composer/_action';
                $data['payloadModel'] = new Payload;
                break;

            case 'recipients':
                $view = 'composer/_recipients';
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