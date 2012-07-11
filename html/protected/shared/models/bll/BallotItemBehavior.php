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

}

?>
