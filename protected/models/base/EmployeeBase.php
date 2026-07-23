<?php

/**
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $mobile_phone
 * @property string $email
 * @property string $identity_number
 * @property string $tax_personal_number
 * @property string $position
 * @property integer $is_inactive
 */
class EmployeeBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_employee';
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('email', 'email'),
            array('is_inactive', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            array('mobile_phone, position', 'length', 'max' => 60),
            array('email, identity_number, tax_personal_number', 'length', 'max' => 20),
            array('address', 'safe'),
            // The following rule is used by search().
            array('id, name, address, mobile_phone, email, identity_number, tax_personal_number, position, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Nama',
            'address' => 'Alamat',
            'mobile_phone' => 'HP #',
            'email' => 'Email',
            'identity_number' => 'KTP #',
            'tax_personal_number' => 'NPWP #',
            'position' => 'Posisi',
            'is_inactive' => 'Is Inactive',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.address', $this->address, true);
        $criteria->compare('t.mobile_phone', $this->mobile_phone, true);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.identity_number', $this->identity_number, true);
        $criteria->compare('t.tax_personal_number', $this->tax_personal_number, true);
        $criteria->compare('t.position', $this->position, true);
        $criteria->compare('t.is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
