<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 *
 * @property CategoryBrandType[] $categoryBrandTypes
 * @property CategoryType[] $categoryTypes
 * @property Product[] $products
 */
class TypeBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_type';
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('is_inactive', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 60),
            // The following rule is used by search().
            array('id, name, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'categoryBrandTypes' => array(self::HAS_MANY, 'CategoryBrandType', 'type_id'),
            'categoryTypes' => array(self::HAS_MANY, 'CategoryType', 'type_id'),
            'products' => array(self::HAS_MANY, 'Product', 'type_id'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}