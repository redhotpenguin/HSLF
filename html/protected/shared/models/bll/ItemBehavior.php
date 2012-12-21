<?php

/**
 * External Behaviors for the Item class
 *
 * @author jonas
 */
class ItemBehavior extends CActiveRecordBehavior {

    public function beforeSave($event) {

        if (!empty($this->owner->url)) {

            $items = Item::model()->findAllByAttributes(
                    array('url' => $this->owner->url), 'id!=:the_id', array(':the_id' => $this->owner->id)
            );

            if ($items) {
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
        // validation for a new record ( no item id avalaible)
        else {
            if ($this->isURLUnique($url)) {
                return $this->filterSlug($url);
            }
        }
    }

    /**
     * Make sure an URL is unique
     * @param string $slug slug to be validated
     * &param integer $item_id id of the item (optionnal)
     * @return boolean true = unique . false = already used
     */
    public function isURLUnique($slug, $item_id = null) {
        // avoid returning false positive
        if (isset($item_id)) {
            $items = Item::model()->findAllByAttributes(
                    array('slug' => $slug), 'id!=:item_id', array(':item_id' => $item_id)
            );
        } else {
            $items = Item::model()->findAllByAttributes(
                    array('slug' => $slug));
        }

        if ($items)
            return false;
        else
            return true;
    }

}

?>
