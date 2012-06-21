<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of APISearch
 *
 * @author jonas
 */
class APISearch implements Searchable {
 
   public function search($model_name, $query){
       
       if(strlen($query) < 3)
           return false;

       if(method_exists($this, $model_name.'Search')){
          return $this->{$model_name.'Search'}($query);
       }else
           throw new CException($model_name.'Search() is not implemented');

     }
     
     private function ballotItemSearch($query){
         
        $ballotItemFinder = new BallotItemFinder();
        
        $ballotItemFinder->setPublished('yes');
        $ballotItemFinder->setRunningOnly();
        
        $ballotItemFinder->setName($query, ModelFinder::ILIKE);
        
        try{
          $search_result =  $ballotItemFinder->search();
        }
        catch(Exception $e){
            echo $e->getMEssage();
        }
         return $search_result;
         
     }
}

?>
