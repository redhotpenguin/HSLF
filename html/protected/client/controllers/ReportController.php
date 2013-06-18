<?php

Yii::import("backend.vendors.UrbanAirship.*", false);

use UrbanAirship\ReportClient as ReportClient;

class ReportController extends Controller {

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
                'actions' => array('index'),
                'roles' => array('manageMobileUsers'),
            ),
        );
    }

    /**
     * index page
     */
    public function actionIndex() {
        $tenant = Yii::app()->user->getLoggedInUserTenant();

      //  $reportClient = new ReportClient($tenant->ua_api_key, $tenant->ua_api_secret);

      //  $report = $reportClient->getCurrentMonthReport();

       // $totalPushSent = $report['sends'][0]['ios'] + $report['sends'][0]['android'];

        
        $totalPushSent = 1234;

        $data = array(
            'tenantSettings' => $tenant->getSettingRelation(),
            'userCount' => MobileUser::model()->count(),
            'totalPushSent' => $totalPushSent
        );


        $this->render('index', $data);
    }

}