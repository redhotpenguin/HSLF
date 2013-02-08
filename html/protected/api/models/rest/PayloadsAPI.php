<?php

class PayloadsAPI extends APIBase {

    public function __construct() {
        parent::__construct(new Payload);
        $this->cacheDuration = Yii::app()->params->short_cache_duration;
    }

}

