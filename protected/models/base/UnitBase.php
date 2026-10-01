<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 * @property string $coretax_code
 *
 * @property Product[] $products
 */
class UnitBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_unit';
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('is_inactive', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 60),
            array('coretax_code', 'length', 'max' => 20),
            // The following rule is used by search().
            array('id, name, is_inactive, coretax_code', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'products' => array(self::HAS_MANY, 'Product', 'unit_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.coretax_code', $this->coretax_code, true);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}