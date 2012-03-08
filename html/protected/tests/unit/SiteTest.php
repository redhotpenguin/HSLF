<?php
Yii::import('application.controllers.SiteController');
class MessageTest extends CTestCase{
    
    public function testActionFooBar(){
        $site = new SiteController("test");
        
        $returnedMessage = $site->actionFoobar();
        
        $this->assertEquals($returnedMessage, "1oobar");
        
    
    }
    
    public function testActionFooBar1(){
        $site = new SiteController("test");
        
        $returnedMessage = $site->actionFoobar();
        
        $this->assertEquals($returnedMessage, "foo1bar");
        
    
    }
    
    
    
}



?>
