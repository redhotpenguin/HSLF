<?php

/**
 * External Behaviors for the Ballot Item class
 *
 * @author jonas
 */
class BallotItemBehavior extends CActiveRecordBehavior {

    public function beforeSave($event) {

        if (!empty($this->owner->url)) {

            $ballots = BallotItem::model()->findAllByAttributes(
                    array('url' => $this->owner->url), 'id!=:the_id', array(':the_id' => $this->owner->id)
            );

            if ($ballots) {
                throw new CHttpException(400, Yii::t('error', 'URL already taken'));
            }
        }
    }

    /**
     * Filter a slug  ( everything after the domain name)
     * @param string $url url to be filtered
     * @return string filtered url
     */
    public function filterSlug($url) {
        
        // replace white spaces by an hypehen
        $url = str_replace(" ", "-", $url);

        // build a slug
        $url = preg_replace('/[^\w-]+/i', '', $url);
        $url = strtolower($url);

        //remove utf8 characters
        $url = preg_replace('/[^(\x20-\x7F)]*/', '', $url);

        return $url;
    }

    /**
     * Make sure an URL is unique and filter it
     * @param string $url url to be validated
     * @return string validated url
     */
    public function validateURL($url) {

        // if existing record
        if ($this->owner->id) {
            if ($this->isURLUnique($url, $this->owner->id)) {
                return $this->filterSlug($url);
            }
        }
        // validation for a new record ( no ballot id avalaible)
        else {
            if ($this->isURLUnique($url)) {
                return $this->filterSlug($url);
            }
        }
    }

    /**
     * Make sure an URL is unique
     * @param string $url url to be validated
     * &param integer $ballot_item_id id of the ballot item (optionnal)
     * @return boolean true = unique . false = already used
     */
    public function isURLUnique($url, $ballot_item_id = null) {
        // avoid returning false positive
        if (isset($ballot_item_id)) {
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
