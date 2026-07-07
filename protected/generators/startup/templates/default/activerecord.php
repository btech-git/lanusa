<?php echo "<?php\n"; ?>

class ActiveRecord extends CActiveRecord
{
	const ACTIVE = 0;
	const INACTIVE = 1;
	const ACTIVE_LITERAL = 'Active';
	const INACTIVE_LITERAL = 'Inactive';

	public function getStatus()
	{
		return ($this->is_inactive) ? self::INACTIVE_LITERAL : self::ACTIVE_LITERAL;
	}

	public function scopes()
	{
		$alias = $this->getTableAlias();
		
		return array(
			'active'=>array(
				'condition'=>"{$alias}.is_inactive = :is_inactive",
				'params'=>array(':is_inactive'=>self::ACTIVE),
			),
			'inactive'=>array(
				'condition'=>"{$alias}.is_inactive = :is_inactive",
				'params'=>array(':is_inactive'=>self::INACTIVE),
			),
			'latest'=>array(
				'order'=>"{$alias}.id DESC",
			),
			'oldest'=>array(
				'order'=>"{$alias}.id ASC",
			),
		);
	}

	public function limit($num = 10)
	{
		$this->getDbCriteria()->mergeWith(array('limit'=>$num));

		return $this;
	}
}