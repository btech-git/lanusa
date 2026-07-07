<?php

/**
 * @property integer $id
 * @property integer $category_brand_id
 * @property integer $disc_material_id
 * @property integer $is_inactive
 *
 * @property DiscMaterial $discMaterial
 * @property CategoryBrand $categoryBrand
 */
class CategoryBrandDiscBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_brand_disc';
    }

    public function rules() {
        return array(
            array('category_brand_id, disc_material_id', 'required'),
            array('category_brand_id, disc_material_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_brand_id, disc_material_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'discMaterial' => array(self::BELONGS_TO, 'DiscMaterial', 'disc_material_id'),
            'categoryBrand' => array(self::BELONGS_TO, 'CategoryBrand', 'category_brand_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_brand_id' => 'Category Brand',
            'disc_material_id' => 'Disc Material',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_brand_id', $this->category_brand_id);
        $criteria->compare('disc_material_id', $this->disc_material_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}