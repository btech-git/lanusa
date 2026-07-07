<?php

/**
 * @property integer $id
 * @property string $name
 * @property integer $is_inactive
 *
 * @property CategoryBrand[] $categoryBrands
 * @property CategoryClassification[] $categoryClassifications
 * @property CategoryConnection[] $categoryConnections
 * @property CategoryGrade[] $categoryGrades
 * @property CategoryMaterial[] $categoryMaterials
 * @property CategorySpecification[] $categorySpecifications
 * @property CategoryThickness[] $categoryThicknesses
 * @property CategoryType[] $categoryTypes
 * @property CategoryVariety[] $categoryVarieties
 * @property Product[] $products
 */
class CategoryBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category';
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
            'categoryBrands' => array(self::HAS_MANY, 'CategoryBrand', 'category_id'),
            'categoryClassifications' => array(self::HAS_MANY, 'CategoryClassification', 'category_id'),
            'categoryConnections' => array(self::HAS_MANY, 'CategoryConnection', 'category_id'),
            'categoryGrades' => array(self::HAS_MANY, 'CategoryGrade', 'category_id'),
            'categoryMaterials' => array(self::HAS_MANY, 'CategoryMaterial', 'category_id'),
            'categorySpecifications' => array(self::HAS_MANY, 'CategorySpecification', 'category_id'),
            'categoryThicknesses' => array(self::HAS_MANY, 'CategoryThickness', 'category_id'),
            'categoryTypes' => array(self::HAS_MANY, 'CategoryType', 'category_id'),
            'categoryVarieties' => array(self::HAS_MANY, 'CategoryVariety', 'category_id'),
            'products' => array(self::HAS_MANY, 'Product', 'category_id'),
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