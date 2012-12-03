<?php

/**
 * This is the model class for table "ballot_item_news".
 *
 * The followings are the available columns in table 'ballot_item_news':
 * @property integer $id
 * @property integer $ballot_item_id
 * @property string $title
 * @property string $content
 * @property string $excerpt
 * @property string $date_published
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property BallotItem $ballotItem
 */
class BallotItemNews extends CBaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BallotItemNews the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ballot_item_news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ballot_item_id, title, date_published, slug', 'required'),
            array('ballot_item_id', 'numerical', 'integerOnly' => true),
            array('content, excerpt, tenant_id', 'safe'),
            array('slug', 'slugValidation'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, ballot_item_id, title, content, excerpt, date_published, slug', 'safe', 'on' => 'search'),
        );
    }

    public function slugValidation($attribute, $params) {
        if (!preg_match('/^[\w-]+$/', $this->slug))
            $this->addError($attribute, "Invalid slug");
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ballotItem' => array(self::BELONGS_TO, 'BallotItem', 'ballot_item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ballot_item_id' => 'Ballot Item',
            'title' => 'Title',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
            'date_published' => 'Date Published',
            'slug' => 'Slug',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('ballot_item_id', $this->ballot_item_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('excerpt', $this->excerpt, true);
        $criteria->compare('date_published', $this->date_published, true);
        $criteria->compare('slug', $this->slug, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getExcerpt($id = null) {
        if (!empty($id))
            $this->id = id; // ?

        if ($this->excerpt)
            return $this->excerpt;

        // generate an excerpt from the content
        if ($this->content) {

            $excerpt = strip_tags($this->content);
            return substr($excerpt, 0, 250);
        }
        else
            return false;
    }
    
    
      public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}