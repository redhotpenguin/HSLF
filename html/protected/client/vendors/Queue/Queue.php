<?php

Yii::import('backend.vendors.Queue*');


require_once('vendor/autoload.php');




require_once('lib/Resque.php');

/**
 * Queuing component
 * Uses PHP Resque
 * Usage: Yii::app()->queue->enqueue('my_queue', 'My_Job', array('foo'=>'bar'));
 */
class Queue extends CApplicationComponent {

    public $redis_host;
    public $redis_password;
    public $redis_db;
    public $redis_port;

    
    /**
     * Initialize Resque
     * Throw QueueException if it can't connect to Redis
     */
    public function init() {
        try {
            Resque::setBackend($this->redis_host . ":" . $this->redis_port, null);
            Resque::redis()->auth($this->redis_password);
            Resque::redis()->select($this->redis_db);
        } catch (Exception $e) {
            throw new QueueException("Could not connect to Redis server: " . $e->getMessage());
        }
    
    }

    
    /**
     * Add a job to the queue
     * @param string $queueName queue name
     * @param string $jobName job name
     * @param array $args job arguments
     * @param boolean whether a job should be tracked or not
     * @return string job token
     */
    public function enqueue($queueName, $jobName, $args = array(), $tracking = false) {
        return Resque::enqueue($queueName, $jobName, $args, $tracking);
    }
    
    /**
     * Return the status for a tracked job
     * @param string $token token returned by enque()
     * @return integer 1:waiting 2:running 3:failed 4:complete 
     */
    public function getStatus($token){
        $status = new Resque_Job_Status($token);
                
        if($status)
            return $status->get();
    }

}

class QueueException extends Exception {
    
}