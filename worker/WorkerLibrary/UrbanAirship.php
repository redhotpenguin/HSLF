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
        
        // ios payload
        if (!empty($deviceTokens)) {
            $payload = $extra;
            $payload['device_tokens'] = $deviceTokens;
            $payload['aps']['alert'] = $message;
        }

        // android payload
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
        
       // printf("json: %s\n", $jsonPayload);


        curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return ($status === 200);
    }

}
