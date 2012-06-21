<?php

class BallotController extends Controller {

    public $layout = '//layouts/main';

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $ballots = BallotItem::model()->findAll();

        $this->render('index', array('ballots' => $ballots));
    }

    public function actionView() {
        $year = $_GET['year'];
        $slug = $_GET['slug'];
        
        $ballot = BallotItemManager::findByPublishedYearAndUrl($year, $slug);
        
        if($ballot)
            $this->render('view', array(
                'ballot' => $ballot,
            ));
        else
              throw new CHttpException(404,'The specified post cannot be found.');
    }

    public function actionList() {
    //    echo 'list';
       // print_r($_GET);
   
    }

    public function actionListByDistrict() { // /ballot/ca/congressional/12
        // todo: sanitize and validate get parameters
        $this->setPageTitle('custom page title');
        
        $state_abbr = $_GET['state_abbr'];
        $district_type = $_GET['district_type'];
        $district = $_GET['district'];


        $ballots = BallotItemManager::findAllByDistrict($state_abbr, $district_type, $district);
        
        if(empty($ballots)){
            throw new CHttpException(404,'The specified post cannot be found.');
        }

        
        $this->render('list', array(
            'state_abbr' => $state_abbr,
            'district_type' => $district_type,
            'district' => $district,
            'ballots' => $ballots)
        );
    }

}