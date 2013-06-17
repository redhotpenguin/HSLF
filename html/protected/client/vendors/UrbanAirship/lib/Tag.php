<?php

namespace UrbanAirship\lib;

/**
 * Represent a tag
 * Concrete implementation of Node
 */
class Tag extends Node {

    private $parentCondition;

    /**
     * Constructor for the Node class
     * @param string node name
     * @param Condition parent condition
     */
    public function __construct($name, Condition $parentCondition) {
        $this->name = $name;
        $this->parentCondition = $parentCondition;
    }

    /**
     * Return the parent condition for this node
     * @return Condition
     */
    public function getParentCondition() {
        return $this->parentCondition;
    }

}