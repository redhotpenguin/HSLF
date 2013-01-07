<?php

namespace WorkerLibrary;

/**
 * UrbanAirship API Client
 *
 * @author jonas
 */
class UrbanAirship {

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

    public function sendPushNotification($message, array $deviceTokens = null, array $apids = null, $extra = array()) {
        //POST to /api/push/ 
        /*
         * 
         * 
          {
          "apids": [
          "some APID",
          "another APID"
          ],
          "android": {
          "alert": "Hello from Urban Airship!",
          "extra": {"a_key":"a_value"} #android extra
          },
          "device_tokens": [
          "some device token",
          "another device token"
          ],
          "aps": {
          "alert": "Hello from Urban Airship!",
          }

          "foo": "bar"  # ios extra
          }
         */

        $payload = array();

        if (!empty($deviceTokens)) {
            $payload = $extra;
            $payload['device_tokens'] = $deviceTokens;
        }

        if (!empty($apids)) {
            $payload['apids'] = $apids;
            $payload['android'] = array(
                'alert' => $message
            );

            if (!empty($extra)) {
                $payload['android']['extra'] = $extra;
            }
        }

        $jsonPayload = json_encode($payload);

        $ch = curl_init(self::PUSH_API);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
                )
        );
        
        printf("json: %s\n", $jsonPayload);


        $jsonResult = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status === 200) {
            $result = json_decode($jsonResult, true);
            
            if (isset($result['push_id'])) {
                return $result['push_id'];
            }
        } else {
            return false;
        }
    }

}
