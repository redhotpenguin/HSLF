<?php

/**
 * UrbanAirship API Client
 * API Doc: http://docs.urbanairship.com/reference/api/segments.html
 * @author jonas
 */
class SegmentClient extends UrbanAirshipClient {

    /**
     * GET /api/segments/
     * List all of the segments for the application.
     * @todo: query paginated data by using next_page
     */
    public function getSegments() {
        
        $jsonResult = $this->getJsonData('/segments?limit=100');

        $result = json_decode($jsonResult, false);

        if (property_exists($result, 'segments')) {
            return $result->segments;
        } else {
            return array();
        }
    }

}
