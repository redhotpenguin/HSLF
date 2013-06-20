<?php

Yii::import("backend.vendors.UrbanAirship.*", false);

use UrbanAirship\ReportClient as ReportClient;

class ReportController extends Controller {

    /**
     * @var ReportClient
     */
    private $reportClient;

    /**
     * @var Tenant
     */
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
                'actions' => array('index', 'jsonPushReport', 'jsonUserRegistrationReport', 'jsonResponseReport'),
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

    function validateDate($date) {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false)
                return false;

            return $input_time == $time;
        } else {
            return false;
        }
    }

    /**
     * Print JSON reports
     */
    public function actionJsonPushReport($start = null, $end = null) {
        try {
            if ($start && $end) {
                $response = $this->reportClient->getReport($start, $end, 'DAILY');
            } else {
                $response = $this->reportClient->getCurrentMonthReport('DAILY');
            }
            header('Content-type: ' . 'application/json;charset=UTF-8');
            echo json_encode($response);
            Yii::app()->end();
        } catch (Exception $e) {
            
        }
    }

    /**
     * Print all the users registered for the month of June (JSON)
     */
    public function actionJsonUserRegistrationReport() {
        $start = new MongoDate(strtotime(date("Y-m-01") . " 00:00:00"));

        $registrations = MobileUser::model()->getCountSinceDate($start);

        $androidCount = MobileUser::model()->count(array('device_type' => 'android', 'registration_date' => array('$gt' => $start)));
        $iosCount = MobileUser::model()->count(array('device_type' => 'ios', 'registration_date' => array('$gt' => $start)));


        $result = array(
            'android' => $androidCount,
            'ios' => $iosCount,
            'registrations' => $registrations
        );

        header('Content-type: ' . 'application/json;charset=UTF-8');
        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionJsonResponseReport() {
        $start = date("Y-m-01") . "%2000:00:00";
        $end = date("Y-m-t") . "%2023:59:59";
        $precision = "DAILY";

        header('Content-type: ' . 'application/json;charset=UTF-8');
        echo json_encode($this->reportClient->getResponseReport($start, $end, $precision));
        Yii::app()->end();
    }

}