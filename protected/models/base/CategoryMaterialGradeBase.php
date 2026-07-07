<?php

/**
 * @property integer $id
 * @property integer $category_material_id
 * @property integer $grade_id
 * @property integer $is_inactive
 *
 * @property Grade $grade
 * @property CategoryMaterial $categoryMaterial
 * @property CategoryMaterialGradeBrand[] $categoryMaterialGradeBrands
 * @property CategoryMaterialGradeThickness[] $categoryMaterialGradeThicknesses
 */
class CategoryMaterialGradeBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_material_grade';
    }

    public function rules() {
        return array(
            array('category_material_id, grade_id', 'required'),
            array('category_material_id, grade_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_material_id, grade_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'grade' => array(self::BELONGS_TO, 'Grade', 'grade_id'),
            'categoryMaterial' => array(self::BELONGS_TO, 'CategoryMaterial', 'category_material_id'),
            'categoryMaterialGradeBrands' => array(self::HAS_MANY, 'CategoryMaterialGradeBrand', 'category_material_grade_id'),
            'categoryMaterialGradeThicknesses' => array(self::HAS_MANY, 'CategoryMaterialGradeThickness', 'category_material_grade_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_material_id' => 'Category Material',
            'grade_id' => 'Grade',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_material_id', $this->category_material_id);
        $criteria->compare('grade_id', $this->grade_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}