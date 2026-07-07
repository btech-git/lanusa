<?php

class Admin extends AdminBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
    
	public function beforeSave()
	{
		if (!empty($this->new_password) && !empty($this->confirm_password))
			$this->password = md5($this->new_password);

		return true;
	}

	/**
	 * Authenticates the 'current_password'.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors())
		{
			if (!empty($this->new_password) && !empty($this->confirm_password))
			{
				if (empty($this->current_password))
					$this->addError('current_password', 'Current Password cannot be blank.');
				else if (md5($this->current_password) !== $this->password)
					$this->addError('current_password', 'Password is incorrect');
			}
		}
	}

	public function afterFind()
	{
		parent::afterFind();

		$auth = Yii::app()->authManager;

		$authItems = array_keys($auth->getAuthItems(null, $this->id));
		$this->roles = empty($authItems) ? array() : array_combine($authItems, $authItems);
	}

	public function afterSave()
	{
		parent::afterSave();

		$auth = Yii::app()->authManager;

		if ($this->scenario === 'insert')
		{
			foreach ($this->roles as $role)
			{
				$auth->assign($role, $this->id);
			}
		}
		else
		{
			$authItems = array_keys($auth->getAuthItems(null, $this->id));
			$assignedRoles = empty($authItems) ? array() : array_combine($authItems, $authItems);

			foreach ($this->roles as $role)
			{
				if (!$auth->isAssigned($role, $this->id))
					$auth->assign($role, $this->id);

				unset($assignedRoles[$role]);
			}
			
			foreach ($assignedRoles as $role)
			{
				$auth->revoke($role, $this->id);
			}
		}
	}
}