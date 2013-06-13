<?php

/**
 * UrbanAirship Push API Client
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

        if (!($this->validateId($segmentId))) {
            throw new Exception("Invalid Segment ID");
        }


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

    /**
     * Send a push notification to a single device
     * @param PushNotification $PushNotification notification object to be sent
     * @param string $deviceId device id
     * @return push id if success or throw exception on failure
     */
    public function sendPushNotificationToDevice(PushNotification $pushNotification, $deviceId) {
        $deviceType = $this->findDeviceTypeFromId($deviceId);

        if (!$deviceType) {
            throw new Exception("Device type not supported");
        }

        $payload = $pushNotification->getPayload();

        if ($deviceType === 'android') {
            $container['apids'] = array($deviceId);
            $container['android']['alert'] = $pushNotification->getAlert();
            if (!empty($payload)) {
                $container['android']['extra'] = $payload;
            }
        } else {
            $container['ios'] = $payload;
            $container['ios']['aps']['alert'] = $pushNotification->getAlert();
            $container['device_tokens'] = array($deviceId);
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
     * Find the device type using the device ID (only works with android and iOS)
     * @param $id
     * @return mixed android or ios or false
     */
    public function findDeviceTypeFromId($id) {
        $androidPattern = "/^[a-zA-Z-0-9]{8}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{12}$/";
        $iosPattern = "/^[a-zA-Z-0-9]{64}$/";

        if (preg_match($androidPattern, $id) === 1) {
            return 'android';
        }

        if (preg_match($iosPattern, $id) === 1) {
            return 'ios';
        }

        return false;
    }

}