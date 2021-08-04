<?php

namespace App\Http\Controllers;

use App\Models\Entities\Article;
use Camohub\LaravelDatagrid\Datagrid;


class DatePickersController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		return view('date-pickers.index', ['grid' => $this->getDatagrid()]);
	}


	public function getDatagrid()
	{
		$grid = new Datagrid(Article::with('user')->select('articles.*'));

		$grid->setJSFilterTimeout(100);

		$grid->addColumn('id')
			->setOutherTitleClass('text-center')
			->setOutherClass(function() { return 'colId text-center'; });

		$grid->addColumn('created_at', 'Created')
			->setSort()
			->setRender(function($value, $row) {
				return $value->format('d.m.Y');
			})
			->setFilter(function($model, $value) {
				$explode = explode(' - ', $value);
				$dateFrom = new \DateTimeImmutable(trim($explode[0]));
				$dateTo = new \DateTimeImmutable(trim($explode[1]));
				return $model->where('created_at', '>=', $dateFrom)
					->where('created_at', '<=', $dateTo);
			})
			->setJSFilterPattern('\d{2}\.\d{2}\.\d{4} - \d{2}\.\d{2}.\d{4}');

		$grid->addColumn('created_at', 'Created 2')
			->setSort()
			->setRender(function($value, $row) {
				return $value->format('Y-m-d');
			})
			->setFilter(function($model, $value) {
				$explode = explode(' - ', $value);
				$dateFrom = new \DateTimeImmutable(trim($explode[0]));
				$dateTo = new \DateTimeImmutable(trim($explode[1]));
				return $model->where('created_at', '>=', $dateFrom)
					->where('created_at', '<=', $dateTo);
			})
			->setJSFilterPattern('\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}');

		$grid->addColumn('created_at', 'Created From')
			->setSort()
			->setRender(function($value, $row) {
				return $value->format('Y-m-d');
			})
			->setFilter(function($model, $value) {
				$dateFrom = new \DateTimeImmutable($value);
				return $model->where('created_at', '>=', $dateFrom);
			})
			->setJSFilterPattern('\d{4}-\d{2}-\d{2}');

		$grid->addColumn('created_at', 'Created To')
			->setSort()
			->setRender(function($value, $row) {
				return $value->format('Y-m-d');
			})
			->setFilter(function($model, $value) {
				$dateTo = new \DateTimeImmutable($value);
				return $model->where('created_at', '<=', $dateTo);
			})
			->setJSFilterPattern('\d{4}-\d{2}-\d{2}');

		return $grid;

	}
}
