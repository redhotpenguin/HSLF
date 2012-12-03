<?php

/**
 * This is the model class for table "tenant_option".
 *
 * The followings are the available columns in table 'tenant_option':
 * @property integer $id
 * @property integer $tenant_id
 * @property string $site_url
 * @property string $email
 * @property string $api_key
 * @property string $api_secret
 * @property string $ua_dashboard_link
 *
 * The followings are the available model relations:
 * @property TenantAccount $tenantAccount
 */
class TenantOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TenantOption the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tenant_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tenant_id, site_url, email, api_key, api_secret', 'required'),
			array('tenant_id', 'numerical', 'integerOnly'=>true),
			array('ua_dashboard_link', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tenant_id, site_url, email, api_key, api_secret, ua_dashboard_link', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tenantAccount' => array(self::BELONGS_TO, 'TenantAccount', 'tenant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tenant_id' => 'Tenant Account',
			'site_url' => 'Site Url',
			'email' => 'Email',
			'api_key' => 'Api Key',
			'api_secret' => 'Api Secret',
			'ua_dashboard_link' => 'Ua Dashboard Link',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tenant_id',$this->tenant_id);
		$criteria->compare('site_url',$this->site_url,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('api_key',$this->api_key,true);
		$criteria->compare('api_secret',$this->api_secret,true);
		$criteria->compare('ua_dashboard_link',$this->ua_dashboard_link,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}