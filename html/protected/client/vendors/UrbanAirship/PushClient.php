<?php

/**
 * UrbanAirship API Client
 * API Doc: http://docs.urbanairship.com/reference/api/push.html
 * @author jonas
 */
class PushClient extends UrbanAirshipClient {

    /**
     * Send a push notification using tags
     * @param PushNotification $PushNotification notification object to be sent
     * @param array $tags tags that should receive the notification
     * @return push id if success or throw exception on failure
     */
    public function sendPushNotificationByTags(PushNotification $pushNotification, array $tags) {
        $payload = $pushNotification->getPayload();

        $container = $payload;


        $container['tags'] = $tags;

        $container['aps'] = array(
            'alert' => $pushNotification->getAlert()
        );

        $container['android'] = array(
            'alert' => $pushNotification->getAlert()
        );

        if (!empty($payload)) {
            $container['android']['extra'] = $payload;
        }


        try {
            $jsonResult = $this->postJsonData('/push/', json_encode($container));
            $result = json_decode($jsonResult, true);
            if (isset($result['push_id'])) {
                return $result['push_id'];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Send a broadcast push notification
     * @param PushNotification $PushNotification notification object to be sent
     * @return push id if success or throw exception on failure
     */
    public function sendBroadcastPushNotification(PushNotification $pushNotification) {
        $payload = $pushNotification->getPayload();

        $container = $payload;


        $container['aps'] = array(
            'alert' => $pushNotification->getAlert()
        );

        $container['android'] = array(
            'alert' => $pushNotification->getAlert()
        );

        if (!empty($payload)) {
            $container['android']['extra'] = $payload;
        }

        try {
            $jsonResult = $this->postJsonData('/push/broadcast/', json_encode($container));
            $result = json_decode($jsonResult, true);
            if (isset($result['push_id'])) {
                return $result['push_id'];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Send a push notification to a segment
     * @param PushNotification $PushNotification notification object to be sent
     * @param string $segmentId segment id
     * @return push id if success or throw exception on failure
     */
    public function sendPushNotificationBySegment(PushNotification $pushNotification, $segmentId) {
        $payload = $pushNotification->getPayload();


        $container = array(
            'segments' => array($segmentId)
        );

        $container['ios'] = $payload;
        $container['ios']['aps']['alert'] = $pushNotification->getAlert();

        $container['android']['alert'] = $pushNotification->getAlert();
        if (!empty($payload)) {
            $container['android']['extra'] = $payload;
        }

        try {
            $jsonResult = $this->postJsonData('/push/segments/', json_encode($container));
            $result = json_decode($jsonResult, true);
            if (isset($result['push_id'])) {
                return $result['push_id'];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}