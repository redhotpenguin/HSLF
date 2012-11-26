<?php

class ScorecardsAPI extends APIBase implements IAPI {

    public function __construct() {
        parent::__construct(new Scorecard);
    }

}

?>
