<?php

/**
 * Abstract class representing a simple node
 */
abstract class Node {

    protected $name;

    /**
     * return the name of the node
     * @param string node name
     */
    public function getName() {
        return $this->name;
    }

}