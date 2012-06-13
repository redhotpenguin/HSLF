<?php

/**
 *  Business Logics for the BallotItem model
 *
 * @author jonas
 */
class BallotItemManager {    
    /**
     * Find all the ballot models fora state, by types and by district names. Can also include statewide districts
     * @param string $state_abbr abbreviation of the state
     * @param array $district_types types of the district
     * @param array $district name of the district
     * @param integer $year year of the publication date
     * @param boolean $active  if true, return currently running ballot items
     * @return array return array of ballot items
     */
    public static function findAllByDistricts($state_abbr, array $district_types, array $districts, $year = null, $active = false) {
          
        $district_ids = DistrictManager::getIdsByDistricts($state_abbr, $district_types, $districts);
       
        if(empty($district_ids))
            return false;
        
        
        $ballotItemFinder = new BallotItemFinder();

        $ballotItemFinder->setPublished('yes');
        $ballotItemFinder->orderByHighestPriority();
        $ballotItemFinder->setDistrictIds($district_ids);
        
        if($year){
           $ballotItemFinder->setPublishedYear($year);
           
           if($active)
               $ballotItemFinder->setRunningOnly(); 
        }
       
        $ballots = $ballotItemFinder->search();
        return self::applyFilter($ballots);
    }

    /**
     * Apply set of filters to an array of ballot items
     * @param array $ballots array of ballotItem
     * @return array of filtered ballotItem
     */
    private static function applyFilter($ballots) {
        if (is_array($ballots)) {
            foreach ($ballots as $ballot) {
                $ballot->url = self::addSiteUrlFilter($ballot->url, $ballot->date_published);
            }
        } else {
            $ballots->url = self::addSiteUrlFilter($ballot->url, $ballot->date_published);
        }

        return $ballots;
    }

    /**
     * Prepend the share_url option to the url field
     * @param string $ballot_url url field of the ballotItem 
     * @param string $date_pub publication date of the ballot (format: YYYY-MM-DD HH:MM:SS)
     * @return string filtered ballot item
     */
    private static function addSiteUrlFilter($ballot_url, $date_pub) {
        $share_url = Yii::app()->params['share_url'] . '/ballot';

        return $share_url . '/' . substr($date_pub, 0, 4) . '/' . $ballot_url;
    }

    /**
     * Find all the ballot models by state
     * @param string $state_abbr abbreviation of the state
     * @return array return array of ballot items
     */
    public static function findAllByState($state_abbr) {
        $district_ids = DistrictManager::getIdsByState($state_abbr);

        $ballots = BallotItem::model()->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids));

        return self::applyFilter($ballots);
    }

    /**
     * Find all the ballot models by state and by type. Can also include statewide districts
     * @param string $state_abbr abbreviation of the state
     * @param string $district_type type of the district
     * @param bool  $include_state_wide_district if true, include state wide districts
     * @return array return array of ballot items
     */
    public static function findAllByDistrictType($state_abbr, $district_type, $include_state_wide_district = false) {
        $district_ids = DistrictManager::getIdsByDistrictType($state_abbr, $district_type);

        if ($include_state_wide_district) {
            $state_district_ids = District::model()->getIdsByDistrictType($state_abbr, 'statewide');
            // include state wide districts    
            $district_ids = array_merge($district_ids, $state_district_ids);
        }

        $ballots = BallotItem::model()->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids, 'published' => 'yes'));
        return self::applyFilter($ballots);
    }

    /**
     * Find all the ballot models by state, by type and by district name. Can also include statewide districts
     * @param string $state_abbr abbreviation of the state
     * @param string $district_type type of the district
     * @param string $district name of the district
     * @return array return array of ballot items
     */
    public static function findAllByDistrict($state_abbr, $district_type, $district) {

        $district_id = District::model()->findByAttributes(array(
                    'state_abbr' => $state_abbr,
                    'type' => $district_type,
                    'number' => $district,
                ))->id;


        if (!$district_id)
            return false;

        $ballots = BallotItem::model()->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_id, 'published' => 'yes'));

        return self::applyFilter($ballots);
    }

    /**
     * Find a unique  ballot model by the year and url
     * @param integer $year year of the ballot was published
     * @param string url  url of the ballot model
     * @return object return a ballot_item object
     */
    public static function findByPublishedYearAndUrl($year, $url) {
        return BallotItem::model()->findByAttributes(
                        array(
                    'url' => $url,
                        ), array('condition' => "date_published BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59' AND published='yes' ")
        );
    }

}

?>
