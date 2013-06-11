<?php

/**
 * UrbanAirship API Client
 * API Doc: http://docs.urbanairship.com/reference/api/push.html
 * @author jonas
 */
class PushClient {

    const PUSH_API = 'https://go.urbanairship.com/api/push/';

    /**
     * API Key
     * @var string 
     */
    private $apiKey;

    /**
     * API Master Secret
     * @var string 
     */
    private $apiSecret;

    /**
     * Constructor
     * @param string $apiKey api key
     * @param string $apiSecret api secret
     */
    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Send a push notification
     * @param PushNotification $PushNotification notification object to be sent
     * @return push id if success or throw exception on failure
     */
    public function sendPushNotification(PushNotification $pushNotification) {
        $container = $pushNotification->getPayload();

        $container['tags'] = $pushNotification->getTags();

        $container['aps'] = array(
            'alert' => $pushNotification->getAlert()
        );

        $container['android'] = $pushNotification->getPayload() + array(
            'alert' => $pushNotification->getAlert()
        );

        try {
            $jsonResult = $this->postJsonData(json_encode($container));
            $result = json_decode($jsonResult, true);
            if (isset($result['push_id'])) {
                return $result['push_id'];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Post JSON data to UA API
     * @param string $data (json format)
     * @return result or throw exception
     */
    public function postJsonData($data) {
        $ch = curl_init(self::PUSH_API);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',)
        );

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($status === 200) {
            return $result;
        }

        throw new Exception($result);
    }

}