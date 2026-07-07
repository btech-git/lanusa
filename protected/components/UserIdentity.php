<?php

class UserIdentity extends CUserIdentity
{
	private $_id;

	public function getId()
	{
		return $this->_id;
	}

	public function authenticate()
	{
		$record = Admin::model()->active()->findByAttributes(array('username'=>$this->username));

		if ($record === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		else if ($record->password !== md5($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id = $record->id;
			$this->setState('table', $record->tableName());
			$this->setState('branch_id', $record->branch_id);
			$this->errorCode = self::ERROR_NONE;
		}

		return !$this->errorCode;
	}
}