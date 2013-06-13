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
            return array_map(function($rawSegment) {
                                $segment = new Segment();
                                return $segment->setId($rawSegment->id)->setDisplayName($rawSegment->display_name);
                            }, $result->segments);
        } else {
            return array();
        }
    }

    /**
     * GET /api/segments/<segment_id>
     * List all of the segments for the application.
     * @todo: query paginated data by using next_page
     */
    public function getSegment($segmentId) {
        if (!($this->validateId($segmentId))) {
            throw new Exception("Invalid Segment ID");
        }
        
        $segment = new Segment();

        $jsonResult = $this->getJsonData('/segments/' . $segmentId);

        $rawSegment = json_decode($jsonResult, true);

        $segment->setDisplayName($rawSegment['display_name']);
        $segment->setCriteria($rawSegment['criteria']);

        return $segment;
    }

}
