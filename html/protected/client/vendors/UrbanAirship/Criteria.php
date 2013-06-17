<?php

/**
 * Represent a segment criteria (tree structure)
 */
class Criteria {

    private $rootCondition;

    /**
     * Constructor - Initialize a Criteria
     * @param string - root condition
     */
    public function __construct($name) {
        $this->rootCondition = new Condition($name);
    }

    /**
     * Return the criteria as JSON
     * @return string json criteria
     */
    public function toJson() {

        $result = $this->toArrayRecursive($this->rootCondition->getChildren());

        $container = array(
            $this->rootCondition->getName() => $result
        );

        return json_encode($container);
    }

    /**
     * Convert nodes to an array recursively
     * @param array $nodes array of Node object
     * @return array
     */
    private function toArrayRecursive(array $nodes = array()) {
        $result = array();
        foreach ($nodes as $node) {
            if ($node instanceof Tag) {
                array_push($result, array('tag' => $node->getName()));
            } else {
                array_push($result, array($node->getName() => $this->toArrayRecursive($node->getChildren())));
            }
        }

        return $result;
    }

    /**
     * return the root condition
     * @return Condition
     */
    public function getRootCondition() {
        return $this->rootCondition;
    }

}