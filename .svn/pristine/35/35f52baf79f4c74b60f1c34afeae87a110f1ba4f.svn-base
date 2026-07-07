<?php

/**
 * @property string $id
 * @property string $time
 * @property string $table_name
 * @property integer $record_id
 * @property string $old_data
 * @property string $new_data
 * @property string $user_data
 * @property string $user_table
 */
class HistoryBase extends CActiveRecord {

    public function tableName() {
        return 'tblla_history';
    }

    public function rules() {
        return array(
            array('time, table_name, record_id', 'required'),
            array('record_id', 'numerical', 'integerOnly' => true),
            array('table_name, user_table', 'length', 'max' => 255),
            array('old_data, new_data, user_data', 'safe'),
            // The following rule is used by search().
            array('id, time, table_name, record_id, old_data, new_data, user_data, user_table', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'time' => 'Time',
            'table_name' => 'Table Name',
            'record_id' => 'Record',
            'old_data' => 'Old Data',
            'new_data' => 'New Data',
            'user_data' => 'User Data',
            'user_table' => 'User Table',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('time', $this->time, true);
        $criteria->compare('table_name', $this->table_name, true);
        $criteria->compare('record_id', $this->record_id);
        $criteria->compare('old_data', $this->old_data, true);
        $criteria->compare('new_data', $this->new_data, true);
        $criteria->compare('user_data', $this->user_data, true);
        $criteria->compare('user_table', $this->user_table, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}