<?php

class ScorecardItemsAPI extends APIBase implements IAPI {

    public function __construct() {
        parent::__construct(new ScorecardItem);
    }

}

?>
