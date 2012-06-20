<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.controllers.ApiController');

class CandidateIssueTest extends CDbTestCase {



    public function testAddCandidateIssue() {
        $result = false;

         $candidate_issue = new CandidateIssue();
         
         $candidate_issue->candidate_id = 814;
         $candidate_issue->name = 'jonas';
            $candidate_issue->value = 'Y';
         $candidate_issue->detail = 'test';
         $candidate_issue->save();
            
        $this->assertEquals(true, $result);
    }

}

?>