<?php

class CompletionController extends Controller
{
	public function actionProduct()
	{
		$items = Product::model()->active()->findAll(array(
			'condition' => 'name LIKE :name',
			'params' => array(':name' => '%' . $_GET['term'] . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->code . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->name,
			);
		}

		echo CJSON::encode($rows);
	}

	public function actionSupplier()
	{
		$items = Supplier::model()->active()->findAll(array(
			'condition' => 'name LIKE :name OR company LIKE :company',
			'params' => array(':name' => '%' . $_GET['term'] . '%', ':company' => '%' . $_GET['term'] . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->company . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->company,
			);
		}

		echo CJSON::encode($rows);
	}

	public function actionCustomer()
	{
		$items = Customer::model()->active()->findAll(array(
			'condition' => 'name LIKE :name OR company LIKE :company',
			'params' => array(':name' => '%' . $_GET['term'] . '%', ':company' => '%' . $_GET['term'] . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->company . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->company,
			);
		}

		echo CJSON::encode($rows);
	}

	public function actionAccount()
	{
		$items = Account::model()->active()->findAll(array(
			'condition' => 'name LIKE :name OR code LIKE :code',
			'params' => array(':name' => '%' . $_GET['term'] . '%', ':code' => '%' . $_GET['term'] . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->code . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->name,
			);
		}

		echo CJSON::encode($rows);
	}
	
	public function actionAjaxJsonSupplier($term, $branchId)
	{
		$items = Supplier::model()->active()->findAll(array(
			'condition' => 'branch_id = :branch_id AND (name LIKE :name OR company LIKE :company)',
			'params' => array(':branch_id' => $branchId, ':name' => '%' . $term . '%', ':company' => '%' . $term . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->company . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->company,
			);
		}

		echo CJSON::encode($rows);
	}
	
	public function actionAjaxJsonCustomer($term, $branchId)
	{
		$items = Customer::model()->active()->findAll(array(
			'condition' => 'branch_id = :branch_id AND (name LIKE :name OR company LIKE :company)',
			'params' => array(':branch_id' => $branchId, ':name' => '%' . $term . '%', ':company' => '%' . $term . '%'),
			'limit' => 10,
		));

		$rows = array();
		foreach ($items as $item)
		{
			$rows[] = array(
				'label' => $item->company . ' - ' . $item->name,
				'value' => $item->id,
				'id' => $item->company,
			);
		}

		echo CJSON::encode($rows);
	}
}