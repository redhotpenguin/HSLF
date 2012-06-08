<?php

/**
 * This is the shortcut to Yii::app()
 */
function app()
{
    return Yii::app();
}


/**
 * Encapsulate Yii::app()->request->getPost()
 */
function getPost($name){
    return  Yii::app()->request->getPost($name);
}


function isPost($name){
   return ( getPost($name) != false );
}


/**
 * Encapsulate Yii::app()->request->getParam()
 */
function getParam($name){
    return Yii::app()->request->getParam($name);
}

function isParam($name){
   return (  getParam($name) != false );
}
