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
    public function actionJsonPushReport() {
        header('Content-type: ' . 'application/json;charset=UTF-8');
        $cacheKey = $this->tenant->id . '_actionJsonPushReport';

        if (($cachedJsonResult = Yii::app()->cache->get($cacheKey)) == true) {
            echo $cachedJsonResult;
        } else {
            try {

                $start = date('Y-m-d H:i:s', strtotime("-1 year", time())); // 365 days ago

                $end = date('Y-m-d H:i:s', time()); // now

                $response = $this->reportClient->getReport($start, $end, 'MONTHLY');

                $jsonResult = json_encode($response);

                Yii::app()->cache->set($cacheKey, $jsonResult, 600); // cache json result for 10 minutes

                echo $jsonResult;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        Yii::app()->end();
    }

    /*
     * This has bad time complexity
     */

    private function findRecord($registrations, $date) {
        foreach ($registrations as $registration) {
            if ($registration['date'] == $date) {
                return $registration;
            }
        }

        return false;
    }

    /**
     * Print all the users registered for the month of June (JSON)
     * Terrible time complexity
     */
    public function actionJsonUserRegistrationReport() {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        /*  $cacheKey = $this->tenant->id . '_actionJsonUserRegistrationReport';
          if (($cachedJsonResult = Yii::app()->cache->get($cacheKey)) == true) {
          echo $cachedJsonResult;
          } else {
         */

        $timeStamp = strtotime("-1 year", time());

        $start = new MongoDate($timeStamp);


        $registrations = MobileUser::model()->getCountSinceDate($start, 'YEARLY');

        $mobileUserModel = MobileUser::model();
        $mobileUserModel->setReadPreference(MongoClient::RP_SECONDARY_PREFERRED);

        $androidCount = $mobileUserModel->count(array('device_type' => 'android', 'registration_date' => array('$gt' => $start)));
        $iosCount = $mobileUserModel->count(array('device_type' => 'ios', 'registration_date' => array('$gt' => $start)));


        // $registrations doesn't contain periods with no users
        // pad $registrations with missing periods
        $paddedRegistrations = array();

        for ($i = 0; $i < 13; $i++) { // show 13 months
            $tmp = array(
                'date' => date('n/01/Y', $timeStamp),
                'total' => 0,
                'ios' => 0,
                'android' => 0
            );

            if (( $foundRecord = $this->findRecord($registrations, $tmp['date']))) {
                $tmp = $foundRecord;
            }

            array_push($paddedRegistrations, $tmp);
            $timeStamp += date('t', $timeStamp) * 24 * 3600;
        }


        $result = array(
            'android' => $androidCount,
            'ios' => $iosCount,
            'registrations' => $paddedRegistrations
        );

        $jsonResult = json_encode($result);

        //     Yii::app()->cache->set($cacheKey, $jsonResult, 600); // cache json result for 10 minutes


        echo $jsonResult;
        // }
        Yii::app()->end();
    }

    public function actionJsonResponseReport() {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        $cacheKey = $this->tenant->id . '_actionJsonResponseReport';
        if (($cachedJsonResult = Yii::app()->cache->get($cacheKey)) == true) {
            echo $cachedJsonResult;
        } else {

            $start = date('Y-m-d H:i:s', strtotime("-1 year", time()));

            $end = date('Y-m-d H:i:s', time());

            $jsonResult = json_encode($this->reportClient->getResponseReport($start, $end, 'MONTHLY'));
            Yii::app()->cache->set($cacheKey, $jsonResult, 600); // cache json result for 10 minutes
            echo $jsonResult;
        }

        Yii::app()->end();
    }

}