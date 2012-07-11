<?php

/**
 * External Behaviors for the Ballot Item class
 *
 * @author jonas
 */
class BallotItemBehavior extends CActiveRecordBehavior {

    public function beforeSave() {

        if (!empty($this->owner->url)) {

            $ballots = BallotItem::model()->findAllByAttributes(
                    array('url' => $this->owner->url), 'id!=:the_id', array(':the_id' => $this->owner->id)
            );

            if ($ballots) {
                /*
                  foreach($ballots as $ballot){
                  error_log('here');
                  error_log($ballot->id);
                  }
                 * */
                error_log('url already taken');
                throw new CHttpException(400, Yii::t('error', 'URL already taken'));
            }
        }
    }

    /* EXPERIMENTAL */

    public static function filterURL($url) {


        $url = preg_replace('/\W/', '', $url);


        $url = strtolower($url);

        //remove utf8 characters
        $url = preg_replace('/[^(\x20-\x7F)]*/', '', $url);

        $url = str_replace(array(" ", "_"), "-", $url);
        return $url;
    }

    public function validateURL($url) {
        if (!$this->owner->isNewRecord) {

            if ($this->isURLUnique($url, $this->owner->id)) {
                return $this->filterURL($url);
            }else
                return false;
        }
    }

    public static function isURLUnique($url, $ballot_item_id = null) {
        if (isset($ballot_item_id)) {
            $ballot_item_id;

            $ballots = BallotItem::model()->findAllByAttributes(
                    array('url' => $url), 'id!=:ballot_id', array(':ballot_id' => $ballot_item_id)
            );
        } else {

            $ballots = BallotItem::model()->findAllByAttributes(
                    array('url' => $url));
        }


        if ($ballots)
            return false;
        else
            return true;
    }

}

?>
