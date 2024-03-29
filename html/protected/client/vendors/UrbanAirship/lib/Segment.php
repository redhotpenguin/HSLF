<?php

namespace UrbanAirship\lib;

/**
 * Encapsulate a Segment
 */
class Segment {

    private $id;
    private $displayName;
    private $criteria;

    /**
     * Get Segment ID 
     * @return string id
     */
    public function getId() {
        return $this->id;
    }

    /**
     *  Get Segment display name
     * @return string display name
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * Set Segment criteria
     * @return array criteria
     */
    public function getCriteria() {
        return $this->criteria;
    }

    /**
     * Set Segment ID
     * @param string $id
     * @return Segment
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set Segment display name
     * @param string $displayName
     * @return Segment
     */
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Set Segment $criteria
     * @param array $criteria
     * @return Segment
     */
    public function setCriteria(array $criteria) {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * Extract tags from a criteria (very hacky)
     * @param array $criteria
     * @retrn array of tags
     */
    function getTags() {
        global $criteriaTags;
        $criteriaTags = array();
        $criteria = $this->getCriteria();
        
        array_walk_recursive($criteria, function($item, $key) {
                    global $criteriaTags;

                    if ($key == 'tag') {
                        array_push($criteriaTags, $item);
                    }
                });

        unset($GLOBALS['criteriaTags']);
        return $criteriaTags;
    }

}