<?php

Yii::import("backend.vendors.UrbanAirship.*", false);

use UrbanAirship\ReportClient as ReportClient;

class ReportController extends Controller {

    private $reportClient;
    private $tenant;

    public function __construct() {
        parent::__construct('report');

        $this->tenant = Yii::app()->user->getLoggedInUserTenant();

        $this->reportClient = new ReportClient($this->tenant->ua_api_key, $this->tenant->ua_api_secret);
    }

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
            array('allow',
                'actions' => array('index', 'monthlyPushJsonReport', 'monthlyJsonUserRegistration'),
                'roles' => array('manageMobileUsers'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * index page
     */
    public function actionIndex() {
        $data = array(
            'tenantSettings' => $this->tenant->getSettingRelation(),
            'userCount' => MobileUser::model()->count(),
        );


        $this->render('index', $data);
    }

    /**
     * Print JSON reports
     */
    public function actionMonthlyPushJsonReport() {
        header('Content-type: ' . 'application/json;charset=UTF-8');


        $response = $this->reportClient->getCurrentMonthReport('DAILY');


        echo CJSON::encode($response);
        Yii::app()->end();
    }

    /**
     * Print all the users registered for the month of June (JSON)
     */
    public function actionMonthlyJsonUserRegistration() {
        $start = new MongoDate(strtotime( date("Y-m-01") . " 00:00:00" ));
  
        $registrations = MobileUser::model()->getCountSinceDate($start);

        $count = MobileUser::model()->count(array('registration_date' => array('$gt' => $start)));

        $result = array(
            'total' => $count,
            'registrations' => $registrations
        );

        header('Content-type: ' . 'application/json;charset=UTF-8');
        echo json_encode($result);
        Yii::app()->end();
    }

}