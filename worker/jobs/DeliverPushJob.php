<?php

require_once('config/MobileUserExportJobConfig.php');

/**
 * NOT IMPLEMENTED
 * Send a request to urban airship to deliver a push
 */
class DeliverPushJob {

    public function perform() {
        $startTime = microtime(true);

        $this->log('Starting Push Message Delivery Job');

        error_log(print_r($this->args), true);


        $completeTime = microtime(true) - $startTime;
        $memoryUsed = memory_get_peak_usage(true) / 1024 / 1024;
        $this->log("Used $memoryUsed MB ");
        $this->log("Push message delivered in $completeTime seconds");
    }

    /**
     * log a message
     * @param string message
     * @param string type type of message
     */
    private function log($message) {
        $name = ( isset($this->args['tenant_name']) ? $this->args['tenant_name'] : '');
        printf('%s: ' . $message . PHP_EOL, $name);
    }

    /**
     * log an error message
     */
    private function logError($message) {
        $name = ( isset($this->args['tenant_name']) ? $this->args['tenant_name'] : '');

        printf('%s[error]: ' . $message . PHP_EOL, $name);
    }

    private function verifyArguments() {
        
    }

}