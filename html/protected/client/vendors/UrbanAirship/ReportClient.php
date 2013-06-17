<?php

namespace UrbanAirship;

/**
 * UrbanAirship Report API Client
 * API Doc: http://docs.urbanairship.com/reference/api/push.html
 * @author jonas
 */
class ReportClient extends lib\UrbanAirshipClient {

    /**
     * Return statistics for a single push
     * @param string $pushId push id
     * @return array
     */
    public function getPushReport($pushId) {
        if (!$this->validateId($pushId)) {
            throw new \Exception("Invalid Push ID");
        }

        $jsonResult = $this->getJsonData('/reports/responses/' . $pushId);

        return json_decode($jsonResult, true);
    }

}