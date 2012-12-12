<?php

$this->widget('ext.AjaxFileUploader.AjaxFileUploader', array(
    'attribute' => 'image_url',
    'options' => array('upload_handler' => CHtml::normalizeUrl(array('item/upload')))))->modalWindow();