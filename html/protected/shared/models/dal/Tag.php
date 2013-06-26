<?php

/**
 * This is the model class for table "tag".
 *
 * The followings are the available columns in table 'tag':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $display_name
 */
class Tag extends BaseActiveRecord {

    private $allowedTypes = array('alert', 'organization', 'district');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Tag the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tag';
    }

    public function getTagTypes() {
        return array('alert' => 'Alert', 'organization' => 'Organization', 'district' => 'District');
    }

    public function getAllowedTypes() {
        return $this->allowedTypes;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function remove_spaces($string) {
        return preg_replace("/\s+/", "", $string);
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type', 'length', 'max' => 255),
            array('name, type, display_name', 'required'),
            array('id, name, type, tenant_id, display_name', 'safe', 'on' => 'search'),
            array('name', 'filter', 'filter' => array($this, 'remove_spaces')),
            array('type', 'tagType'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'organizations' => array(self::MANY_MANY, 'Organization',
                'tag_organization(tag_id, organization_id)'),
            'push_messages' => array(self::MANY_MANY, 'PushMessage',
                'tag_push_message(tag_id, push_message_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'display_name' => 'Display Name'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteriaInsensitive();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('display_name', $this->display_name, true);


        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getTagId($tagName) {
        $tag = $this->findByAttributes(array('name' => $tagName));
        if ($tag) {
            return $tag->id;
        }

        return false;
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    /**
     * return a string representation of the object
     * Used by array_diff
     * @return string
     */
    public function __toString() {
        return 'Tag_' . $this->id;
    }

    public function beforeSave() {
        parent::beforeSave();

        if (!empty($this->name)) {

            $tags = Tag::model()->findAllByAttributes(
                    array('name' => $this->owner->name), 'id!=:id', array(':id' => $this->owner->id)
            );

            if ($tags) {
                $this->addError('name', 'Name already used');
            } else {
                return true;
            }
        }
    }

    /**
     * Validators - validate the tag type
     * @param array attributes
     */
    public function tagType($attributes) {
        if(!in_array($this->type, $this->allowedTypes)){
             $this->addError('type', 'Invalid Type');
        }
    }

}