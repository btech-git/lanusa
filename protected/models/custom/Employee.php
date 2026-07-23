<?php

class Employee extends EmployeeBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}