<?php

class TestJob{
    
    public function perform(){
        echo "I am performing. Here is what you gave me: ". print_r($this->args, true );
    }
    
    
}


