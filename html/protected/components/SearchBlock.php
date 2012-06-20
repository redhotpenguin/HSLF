<?php
 
Yii::import('zii.widgets.CPortlet');
 
class SearchBlock extends CPortlet
{

   
 
    protected function renderContent()
    {
        echo CHtml::beginForm(array('search/search'), 'get', array('style'=> 'inline')) .
        CHtml::textField('q', '', array('placeholder'=> 'search...','style'=>'width:140px;')) .
        CHtml::submitButton('Search',array('style'=>'width:70px;')) .
        CHtml::endForm('');
    }
}