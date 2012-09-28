<?php

/**
 * This is the model class for table "endorser".
 *
 * The followings are the available columns in table 'endorser':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $website
 * @property string $image_url
 * @property string $display_name
 * @property string $list_name
 * @property string $slug
 */
class Endorser extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Endorser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'endorser';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, list_name, display_name, slug', 'required'),
            array('name, list_name, slug', 'length', 'max' => 512),
            array('website, image_url', 'length', 'max' => 2048),
            array('description, display_name, list_name', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('website, image_url', 'url'),
            array('id, name, description, website, image_url, display_name, list_name, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ballot_items' => array(self::MANY_MANY, 'BallotItem',
                'endorser_ballot_item(endorser_id, ballot_item_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Full Name',
            'description' => 'Description',
            'website' => 'Website',
            'image_url' => 'Image url',
            'display_name' => 'Name in Title Bar',
            'list_name' => 'Name In Lists',
            'slug' => 'Slug'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteriaInsensitive;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('image_url', $this->image_url, true);
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('slug', $this->slug, true);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                ));
    }

}