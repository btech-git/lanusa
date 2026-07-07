<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $thickness_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Thickness $thickness
 */
class CategoryThicknessBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_thickness';
    }

    public function rules() {
        return array(
            array('category_id, thickness_id', 'required'),
            array('category_id, thickness_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, thickness_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'thickness' => array(self::BELONGS_TO, 'Thickness', 'thickness_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'thickness_id' => 'Thickness',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('thickness_id', $this->thickness_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}