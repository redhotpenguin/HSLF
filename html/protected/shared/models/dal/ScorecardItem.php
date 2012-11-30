<?php

/**
 * This is the model class for table "scorecard_item".
 *
 * The followings are the available columns in table 'scorecard_item':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $office_id
 *
 * The followings are the available model relations:
 * @property Scorecard[] $scorecards
 * @property Office $office
 */
class ScorecardItem extends CBaseActiveRecord {

    public $office_type; //used for admin search

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ScorecardItem the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scorecard_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, office_id', 'required'),
            array('office_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 4096),
            array('description, office_type, tenant_account_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, office_id, office_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scorecards' => array(self::HAS_MANY, 'Scorecard', 'scorecard_item_id'),
            'office' => array(self::BELONGS_TO, 'Office', 'office_id'),
            'ballot_items' => array(self::MANY_MANY, 'BallotItem',
                'scorecard(scorecard_item_id, ballot_item_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'office_id' => 'Office',
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
        $table_alias = $this->getTableAlias(false, false);

        $criteria->with = array('office');

        // search by relationship (district)
        if ($this->office_type) {
            $criteria->together = true;
            // Join the 'district' table


            $criteria->compare('office.name', $this->office_type, false);
        }

        $criteria->compare($table_alias . '.id', $this->id);
        $criteria->compare($table_alias . '.name', $this->name, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                    'sort' => array(
                        'defaultOrder' => $table_alias . '.id DESC',
                        'attributes' => array(
                            'office_type' => array(
                                'asc' => 'office.name ASC',
                                'desc' => 'office.name DESC',
                            ),
                            '*',
                    ))
                ));
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}