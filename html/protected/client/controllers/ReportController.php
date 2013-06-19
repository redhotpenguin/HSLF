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
                'actions' => array('index', 'monthlyJsonReport'),
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
    public function actionMonthlyJsonReport() {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        
        $response = $this->reportClient->getCurrentMonthReport('DAILY');


        echo CJSON::encode($response);
        Yii::app()->end();
    }

}