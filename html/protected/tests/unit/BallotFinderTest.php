<?php



class BallotFinderTest extends CTestCase {

    public function testConstruct() {
        $district_ids = DistrictManager::getIdsByDistrictType('or', 'congressional');
        

        $ballotItemFinder = new BallotItemFinder();
        
       // $ballotItemFinder->setParty("independant");
     //   $ballotItemFinder->setParty("N/A");
        $ballotItemFinder->setDistrictIds($district_ids);
      //   
        $ballotItemFinder->setPublished('yes');
        
        $ballotItemFinder->orderByHighestPriority();
        
      //  $ballotItemFinder->setRunningOnly();
        $ballotItemFinder->setPublishedYear(2014);
        
       // $ballotItemFinder->setPriority(5);
        $ballots = $ballotItemFinder->search();

        // error_log(print_r($ballots, true));

        foreach ($ballots as $ballot) {
            error_log($ballot->item);
        }

        // $this->assertEquals($returnedMessage, "1oobar");
    }

}
