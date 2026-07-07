<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 *
 * @property CategoryClassificationMaterial[] $categoryClassificationMaterials
 * @property CategoryMaterial[] $categoryMaterials
 * @property Product[] $products
 */
class MaterialBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_material';
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
            'categoryClassificationMaterials' => array(self::HAS_MANY, 'CategoryClassificationMaterial', 'material_id'),
            'categoryMaterials' => array(self::HAS_MANY, 'CategoryMaterial', 'material_id'),
            'products' => array(self::HAS_MANY, 'Product', 'material_id'),
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