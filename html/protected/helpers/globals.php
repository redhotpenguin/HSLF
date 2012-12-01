<?php

/**
 * This is the shortcut to Yii::app()
 */
function app() {
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->params['my_param']
 */
function getSetting($setting_name) {
    return Yii::app()->params[$setting_name];
}

/**
 * This is the shortcut to Yii::app()->params['my_param'] = "foobar"
 */
function setSetting($setting_name, $setting_value) {
    Yii::app()->params[$setting_name] = $setting_value;
}

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
 * Return wether the user is an admin or not
 * @return boolean
 */
function isAdmin() {
    return ( "admin" == Yii::app()->user->role );
}

/**
 * Debug function
 * @param mixed $data data to be logged
 */
function logIt($data) {
    error_log(print_r($data, true));
}