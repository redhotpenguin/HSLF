<?php

class SearchController extends Controller {

    public $layout = '//layouts/main';

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        $this->render('index');
    }


}