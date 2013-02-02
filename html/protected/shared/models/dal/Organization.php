<?php

/**
 * This is the model class for table "organization".
 *
 * The followings are the available columns in table 'organization':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $website
 * @property string $image_url
 * @property string $display_name
 * @property string $slug
 * @property string $facebook_url
 * @property string $twitter_handle
 * @tenant_id integer tenant id
 * @address string address
 */
class Organization extends CBaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Organization the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'organization';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, display_name, slug, address', 'required'),
            array('name, slug', 'length', 'max' => 512),
            array('website, image_url', 'length', 'max' => 2048),
            array('facebook_url', 'length', 'max' => 1024),
            array('twitter_handle', 'length', 'max' => 140),
            array('description, display_name, tenant_id', 'safe'),
            array('website, image_url', 'url'),
            array('id, name, description, website, image_url, display_name, slug, facebook_url, twitter_handle, address', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'items' => array(self::MANY_MANY, 'Item',
                'organization_item(organization_id, item_id)'),
            'tags' => array(self::MANY_MANY, 'Tag',
                'tag_organization(organization_id, tag_id)'),
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
            'display_name' => 'Display Name',
            'slug' => 'Slug',
            'address' => 'Address'
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

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior'),
            'TagRelation' => array(
                'class' => 'TagRelationBehavior',
                'joinTableName' => 'tag_organization',
                'tagRelationName' => 'organizations', // relation to this class, defined in Tags.
                'foreignKeyName' => 'organization_id'
            )
        );
    }

}