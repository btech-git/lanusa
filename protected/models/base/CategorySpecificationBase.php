<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $specification_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Specification $specification
 */
class CategorySpecificationBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_specification';
    }

    public function rules() {
        return array(
            array('category_id, specification_id', 'required'),
            array('category_id, specification_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, specification_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'specification' => array(self::BELONGS_TO, 'Specification', 'specification_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'specification_id' => 'Specification',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('specification_id', $this->specification_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}