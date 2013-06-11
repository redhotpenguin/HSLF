<?php

/**
 * External Behaviors for the Pushmessage class
 *
 * @author jonas
 */
class DeliverPushMessageBehavior extends CActiveRecordBehavior {

    /**
     * NOT IMPLEMENTED
     * Queue a push message and send it to a worker
     */
    public function deliverPush() {
        // proof of concept
        /*
        $tags = array();

        if ($this->owner->tags) {
            foreach ($this->owner->tags as $tag) {
                array_push($tags, $tag->name);
            }
        }

        $tenant = Tenant::model()->findByPk($this->owner->payload->tenant_id);
        if (!$tenant) {
            throw new Exception('Invalid tenant ID');
        }

        $credentials = array(
            'api_key' => $tenant->ua_api_key,
            'api_master_secret' => $tenant->ua_api_secret
        );


        $parameters = array(
            'message' => $this->owner->alert,
            'audience' => $tags,
            'credentials' => $credentials
        );
        Yii::app()->queue->enqueue('mobile_platform', 'DeliverPushJob', $parameters);
         *
         */
    }

}