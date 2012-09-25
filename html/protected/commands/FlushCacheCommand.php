<?php

class FlushCacheCommand extends CConsoleCommand {

    public function run($args) {

        $cacheFlushResult = Yii::app()->cache->flush();

        if ($cacheFlushResult) {
            $msg = "Cache successfully flushed";
            echo $msg;
            error_log($cacheFlushResult);
        } else {
            $msg = "Could not flush the cache";
            echo $msg;
            error_log($cacheFlushResult);
        }
    }

}