<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 *
 * @property CategoryBrandConnection[] $categoryBrandConnections
 * @property CategoryClassificationConnection[] $categoryClassificationConnections
 * @property CategoryConnection[] $categoryConnections
 * @property Product[] $products
 */
class ConnectionBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_connection';
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
            'categoryBrandConnections' => array(self::HAS_MANY, 'CategoryBrandConnection', 'connection_id'),
            'categoryClassificationConnections' => array(self::HAS_MANY, 'CategoryClassificationConnection', 'connection_id'),
            'categoryConnections' => array(self::HAS_MANY, 'CategoryConnection', 'connection_id'),
            'products' => array(self::HAS_MANY, 'Product', 'connection_id'),
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