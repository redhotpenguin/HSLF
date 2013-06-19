<?php

namespace UrbanAirship;

/**
 * UrbanAirship Report API Client
 * API Doc: http://docs.urbanairship.com/reference/api/reports.html
 * @author jonas
 */
class ReportClient extends lib\UrbanAirshipClient {

    /**
     * Get: /api/reports/sends/?start=(date)&end=(date)&precision=(precision)
     * Get the number of pushes sent within a specified time period.
     * @param string $start date
     * @param string $end date
     * @param string precision (HOURLY, DAILY, MONTHLY, YEARLY)
     * @return StdObject report
     * @todo: validate arguments
     */
    public function getReport($start, $end, $precision = 'DAILY') {
        $jsonResult = $this->getJsonData("/reports/sends/?start=$start&end=$end&precision=$precision");
        return json_decode($jsonResult, true);
    }

    /**
     * Get the number of pushes sent for the current month
     * @param $precision - optionnal. Default to Monthly - (HOURLY, DAILY, MONTHLY, YEARLY)
     * @return StdObject report
     */
    public function getCurrentMonthReport($precision = "MONTHLY") {

        $start = date("Y-m-01") . "%2000:00:00";
        $end = date("Y-m-t") . "%2023:59:59";
        $result = $this->getReport($start, $end, $precision);
        return $result;
    }

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