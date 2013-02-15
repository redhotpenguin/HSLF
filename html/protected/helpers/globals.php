<?php

/**
 * Encapsulate Yii::app()->request->getPost()
 * @param  string $name POST field name
 * @return string return the POST field value
 */
function getPost($name) {
    return Yii::app()->request->getPost($name);
}

/**
 * Verify that the field name exists in $_POST[]
 * @param  string $name POST field name
 * @return boolean return true if the field name exists in $_POST, false otherwise
 */
function isPost($name) {
    return ( getPost($name) != false );
}

/**
 * Encapsulate Yii::app()->request->getParam()
 * @param  string $name GET field name
 * @return string return the GET field value
 */
function getParam($name) {
    return Yii::app()->request->getParam($name);
}

/**
 * Verify that the field name exists in $_GET[]
 * @param  string $name GET field name
 * @return boolean return true if the field name exists in $_GET, false otherwise
 */
function isParam($name) {
    return ( getParam($name) != false );
}

/**
 * Return a hash version of the input
 * @param  string $string string to be hashed
 * @return string return the hashed input
 */
function get_hash($string) {
    return hash('sha1', $string);
}

/**
 * Debug function
 * @param mixed $data data to be logged
 */
function logIt($data) {
    error_log(print_r($data, true));
}