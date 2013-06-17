<?php

namespace UrbanAirship\lib;

/**
 * Represent a conditition
 * Concrete implementation of Node
 */
class Condition extends Node {

    private $children;

    /**
     * Constructor for the Condition class
     * @param string condition name
     * @param array children of the condition - optional
     */
    public function __construct($name, $children = array()) {
        $this->name = strtolower($name);
        $this->children = $children;
    }

    /**
     * Return the children of the condition
     * @return array array of Node object
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Add a new tag to the condition
     * @param string tag name
     * @return Condition (this)
     */
    public function addTag($tagName) {
        $this->addChild(new Tag($tagName, $this));
    }

    /**
     * Add a child to the current condition
     * @param Node node
     */
    private function addChild(Node $node) {
        array_push($this->children, $node);
    }

    /**
     * Add a new condition to the condition
     * @param string tag name
     * @return Condition (this)
     */
    public function addCondition($conditionName) {
        $condition = new Condition($conditionName);
        $this->addChild($condition);
        return $condition;
    }

    /**
     * Add a new NOT condition to the condition
     * @param string tag name
     * @return Condition (this)
     */
    public function addNotCondition() {
        $condition = new Condition("not");
        $this->addChild($condition);
        return $condition;
    }

    /**
     * Add a new OR condition to the condition
     * @param string tag name
     * @return Condition (this)
     */
    public function addOrCondition() {
        $condition = new Condition("or");
        $this->addChild($condition);
        return $condition;
    }

    /**
     * Add a new AND condition to the condition
     * @param string tag name
     * @return Condition (this)
     */
    public function addAndCondition() {
        $condition = new Condition("and");
        $this->addChild($condition);
        return $condition;
    }

}